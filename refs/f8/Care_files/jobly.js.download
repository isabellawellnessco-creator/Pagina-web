window.addEventListener('message', (e) => {
    if (e.data.app === "jobly" && e.origin === "https://jobly.inspon-cloud.com") {


        if (e.data.type === "height") {
            // if (document.querySelector("#joblyFrame").offsetHeight < e.data.height) {
                document.querySelector("#joblyFrame").style.height = e.data.height + 200 + 'px';
            // }
        }

        if (e.data.type === "redirect") {
            window.location.href = e.data.url;
        }

        if (e.data.type === "job_schema") {
            const script_tag = document.createElement("script");
            script_tag.setAttribute("type", "application/ld+json")
            script_tag.innerHTML = JSON.stringify(e.data.schema);
            document.querySelector("body").appendChild(script_tag);
        }



    }
});