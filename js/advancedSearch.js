var slider1 = document.getElementById("radius");
var output1 = document.getElementById("radius-output");
var slider2 = document.getElementById("minLots");
var output2 = document.getElementById("minLots-output");
var slider3 = document.getElementById("minCarpark");
var output3 = document.getElementById("minCarpark-output");

output1.innerHTML = "Radius: " + slider1.value;
slider1.oninput = function() {
  output1.innerHTML = "Radius: " + this.value;
}

output2.innerHTML = "Available Lots: " + slider2.value;
slider2.oninput = function() {
  output2.innerHTML = "Available Lots: " + this.value;
}

output3.innerHTML = "Available Carparks: " + slider3.value;
slider3.oninput = function() {
  output3.innerHTML = "Available Carparks: " + this.value;
}
