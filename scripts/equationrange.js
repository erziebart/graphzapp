// This class store the t range for an equation
class GraphzappEquationRange {
	constructor(minObj, maxObj) {
		this.minObj = minObj;
		this.maxObj = maxObj;
		this.updateVals();
	}

	// convert values to float
	updateVals() {
		this.min = parseFloat(this.minObj.value);
		this.max = parseFloat(this.maxObj.value);
	}
}
