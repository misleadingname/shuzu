// make a regex that finds the "_thumb" part of the filename including the extension
let thumb_regex = /_thumb\.[a-z]{3,4}$/i;

function embed(thread_id) {
    // set the "filename" exactly as the
    console.log(filename);
    filename.replace(thumb_regex, "");
}