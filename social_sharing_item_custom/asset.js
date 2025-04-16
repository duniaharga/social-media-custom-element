/* Essentials YOOtheme Pro 1.5.8; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

window.UIkit.util.on("a[data-yooessentials-social-popup]","click",function(e){e.preventDefault();e=JSON.parse(this.dataset.yooessentialsSocialPopup);window.UIkit.util.assign(e,{left:(window.screen.width-e.width)/2,top:(window.screen.height-e.height)/4,resizible:"yes",scrollbars:"yes",menubar:"no",location:"no",directories:"no",status:"yes"}),window.open(this.href,"popupWindow",JSON.stringify(e).replace(/"|{|}/g,"").replace(/:/g,"="))});


// Load assets.css dynamically
const cssLink = document.createElement("link");
cssLink.rel = "stylesheet";
cssLink.href = "/assets.css"; // Replace with your correct path
cssLink.type = "text/css";
document.head.appendChild(cssLink);


//copy sharing link 

document.addEventListener("DOMContentLoaded", function () {
    const toast = document.createElement("div");
    toast.className = "copy-toast";
    document.body.appendChild(toast);

    document.body.addEventListener("click", function (e) {
        const shareButton = e.target.closest(".copy-share-link");

        if (!shareButton) return;
        e.preventDefault(); // 🛑 يمنع التحديث
        const currentUrl = window.location.href;
        const message = shareButton.getAttribute("data-message") || "Link copied!";

        navigator.clipboard.writeText(currentUrl).then(() => {
            toast.textContent = message;
            toast.classList.add("show");

            setTimeout(() => {
                toast.classList.remove("show");
            }, 2000);
        });
    });
});

  
