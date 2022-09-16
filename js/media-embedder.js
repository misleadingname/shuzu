function embed(e) {
    let a = e.currentTarget
    let img = e.currentTarget.children[0]

    if(img.classList.contains('fullImage')){
        img.src = a.href + "&thumb=true"
        img.classList.remove('fullImage')
    } else {
        img.src = a.href
        img.classList.add('fullImage')
    }

    e.preventDefault()
    return false
}