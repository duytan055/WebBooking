const seatMap=document.getElementById("seatMap")

let selectedSeats=[]
let seatPrices={}
let comboTotal=0
let comboName=""
let discount=0

function createSeats(rows,type){

rows.forEach(r=>{

let row=document.createElement("div")
row.className="row"

for(let i=1;i<=16;i++){

let seat=document.createElement("div")
let code=r+i.toString().padStart(2,"0")

seat.className=type==="vip"?"seat vip":"seat"
seat.innerText=code

seatPrices[code]=type==="vip"?70000:50000

seat.onclick=()=>selectSeat(seat,code)

row.appendChild(seat)

}

seatMap.appendChild(row)

})

}

createSeats(["C","D","E","F","G","H"],"normal")
createSeats(["I","J"],"vip")

function selectSeat(seat,code){

if(seat.classList.contains("selected")){

seat.classList.remove("selected")
selectedSeats=selectedSeats.filter(s=>s!==code)

}else{

seat.classList.add("selected")
selectedSeats.push(code)

}

updateUI()

}

function updateUI(){

document.getElementById("selectedSeats").innerText=
selectedSeats.length?selectedSeats.join(", "):"..."

let total=selectedSeats.reduce((sum,s)=>sum+seatPrices[s],0)

document.getElementById("seatTotal").innerText=
total.toLocaleString()+" đ"

}

function goCombo(){

if(selectedSeats.length===0){
alert("Vui lòng chọn ghế")
return
}

seatPage.style.display="none"
comboPage.style.display="block"

}

function addCombo(name,price){

comboTotal+=price
comboName=name

document.getElementById("comboTotal").innerText=
comboTotal.toLocaleString()+" đ"

}

function goPayment(){

comboPage.style.display="none"
paymentPage.style.display="block"

let seatMoney=selectedSeats.reduce((sum,s)=>sum+seatPrices[s],0)

document.getElementById("seatCount").innerText=selectedSeats.length

let list=document.getElementById("seatList")
list.innerHTML=""

selectedSeats.forEach(s=>{

list.innerHTML+=`<div class="seatBox">${s}</div>`

})

document.getElementById("seatMoney").innerText=
seatMoney.toLocaleString()+" đ"

document.getElementById("comboInfo").innerText=comboName||"Không có"

document.getElementById("comboMoney").innerText=
comboTotal.toLocaleString()+" đ"

updateFinalTotal(seatMoney,comboTotal)

}

function updateFinalTotal(sMoney,cMoney){

let final=sMoney+cMoney-discount

document.getElementById("finalTotal").innerText=
final.toLocaleString()+" đ"

}

function applyDiscount(){

let code=document.getElementById("discountInput").value

discount=code==="STARLIGHT"?20000:0

document.getElementById("discountMoney").innerText=
discount.toLocaleString()+" đ"

goPayment()

}

function goBack(){

if(paymentPage.style.display==="block"){

paymentPage.style.display="none"
comboPage.style.display="block"

}else if(comboPage.style.display==="block"){

comboPage.style.display="none"
seatPage.style.display="block"

}

}

let timeLeft=300

setInterval(()=>{

let minutes=Math.floor(timeLeft/60)
let seconds=timeLeft%60

document.getElementById("timer").innerText=
(minutes<10?"0"+minutes:minutes)+":"+
(seconds<10?"0"+seconds:seconds)

if(timeLeft<=0){

alert("Hết thời gian giữ ghế")
location.reload()

}

timeLeft--

},1000)