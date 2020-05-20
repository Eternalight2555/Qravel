function modal_onclick_close(){
document.getElementById("modal-content").style.display = "none";
document.getElementById("modal-overlay").style.display = "none";
/* 3秒後に表示するように「display」の値を変えます */
}

function modal_onclick_open(){
document.getElementById("modal-content").style.display = "block";
document.getElementById("modal-overlay").style.display = "block";
/* 3秒後に表示するように「display」の値を変えます */
}