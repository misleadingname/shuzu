{
  inputs = {
    systems.url = "github:nix-systems/default";
    devenv.url = "github:cachix/devenv";
  };

  nixConfig = {
    extra-trusted-public-keys = "devenv.cachix.org-1:w1cLUi8dv3hnoSPGAuibQv+f9TZLr6cv/Hm9XgU50cw=";
    extra-substituters = "https://devenv.cachix.org";
  };

  outputs = { self, nixpkgs, devenv, systems, ... } @ inputs:
    let
      forEachSystem = nixpkgs.lib.genAttrs (import systems);
    in
    {
      packages = forEachSystem (system: {
        devenv-up = self.devShells.${system}.default.config.procfileScript;
      });

      devShells = forEachSystem
        (system:
          let
            pkgs = nixpkgs.legacyPackages.${system};
          in
          {
            default = devenv.lib.mkShell {
              inherit inputs pkgs;
              modules = [
                ({ config, ... }:
                  let
                    stateDir = config.env.DEVENV_STATE + "/php-fpm";
                  in
                  {
                    enterShell = ''
                      exec devenv up
                    '';

                    #processes.php-log.exec = ''
                    #  echo -n > ${stateDir}/php.log
                    #  tail -f ${stateDir}/php.log
                    #'';

                    languages.php.enable = true;
                    languages.php.package = pkgs.php81.buildEnv {
                      extensions = { all, enabled }: with all; enabled ++ [ imagick pdo_sqlite xdebug ];
                    };

                    languages.php.fpm.pools.web = {
                      settings = {
                        "clear_env" = "no";
                        "pm" = "dynamic";
                        "pm.max_children" = 10;
                        "pm.start_servers" = 2;
                        "pm.min_spare_servers" = 1;
                        "pm.max_spare_servers" = 10;
                      };
                    };
                    languages.php.fpm.phpOptions = ''
                      error_log = ${stateDir}/php.log;
                      log_level = warning;
                      log_errors = on;
                      error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT;
                      xdebug.remote_enable=on;
                      xdebug.remote_host=127.0.0.1;
                      xdebug.remote_port=9003;
                      xdebug.mode=debug,coverage;
                    '';

                    services.caddy.enable = true;
                    services.caddy.virtualHosts.":8080" = {
                      extraConfig = ''
                        root public

                        php_fastcgi unix/${config.languages.php.fpm.pools.web.socket} {
                          index /index.php
                          try_files {path} {path}/index.php /index.php
                        }

                        file_server {
                          index off
                        }

                        try_files {path} {path}/index.php
                      '';
                    };
                  })
              ];
            };
          });
    };
}
