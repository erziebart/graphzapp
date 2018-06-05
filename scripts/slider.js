// wrapper around a slider's elements
class GraphzappSlider {
	constructor(sliderObj, minObj, maxObj, valObj) {
		this.sliderObj = sliderObj;
		this.minObj = minObj;
		this.maxObj = maxObj;
		this.valObj = valObj;
	 	this.adjustRange();
	}

	// adjust the range over which slider varies
	adjustRange() {
		this.min = parseFloat(this.minObj.value);
		this.max = parseFloat(this.maxObj.value);
		this.adjustValue();
	}

	// change the slider value
	adjustValue() {
		this.val = (this.sliderObj.value/1000) * (this.max-this.min) + this.min;
	    this.valObj.innerHTML = this.val.toFixed(2);
	}
}