sort=document.querySelector('.sort_ajax')
sort.querySelectorAll('a').forEach((a=> {
    a.addEventListener('click',e=>{
        e.preventDefault();
      var   url=a.getAttribute('href');
        getUrl(url);
    })
}))
async function getUrl(url) {
    var content = document.querySelector('.content_ajax')
    const response=await fetch(url,{
        headers:{
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
            .then((response) => response.json())
            .then((messages) => {console.log("messages");})
    })
    if (response.status>=200 && response.status<300){
        const data = await response.json();
        content.innerHTML=data.content;
    }
    else {
        console.error(response)
    }
}