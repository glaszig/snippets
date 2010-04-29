/**
 * Periodical timer class
 *
 * this class lets you periodically execute an arbitrary function.
 * additionally, you can start, stop and resume the time.
 *
 * Usage:
 * ------
 *
 *  var timer = new Timer(3000);
 *  timer.start(function(timeout) {
 *    console.log('executing timed function after '+timeout+' msec');
 *  });
 *
 *  timer.stop(); // stops the timer
 *  timer.resume(); // continues the timer
 *  timer.replace(anotherFunction); // replaces the executed function with anotherFunction
 *  timer.timeout(5000); // sets the timeout to 5 seconds
 *
 * 
 * REPORT BUGS, PLEASE!
 *
 * @author glaszig at gmail dot com
 *
 */
(function() {

  /**
   * the constructor
   *
   * @param Number the timeout in miliseconds
   * @return Object Instance of the timer
   */
  var Timer = function(t) {
  	this._timeout = t;
  	this._timer = null;
  	this._func = null;
  }
  
  /**
   * start's the timer
   *
   * @param Function the function to run
   * @return void
   */
  Timer.prototype.start = function(func) {
  	var _self = this;
  	_self._func = func;
  	var _callee = arguments.callee;
  	this._timer = setTimeout(function() {
  		func(_self._timeout); _callee.call(_self, func);
  	}, _self._timeout);
  }

  /**
   * stops the timer
   *
   * @return void
   */
  Timer.prototype.stop = function() {
  	clearTimeout(this._timer);
  }

  /**
   * resumes the timer
   *
   * @return void
   */
  Timer.prototype.resume = function() {
  	return this.start(this._func);
  }

  /**
   * sets the timeout to t miliseconds
   *
   * @param Number the timeout in miliseconds
   * @return void
   */
  Timer.prototype.timeout = function(t) {
  	this._timeout = t;
  }

  /**
   * replaces the running function with func
   *
   * @param Function the function to run
   * @return void
   */
  Timer.prototype.replace = function(func) {
  	this._func = func;
  }
  
  window.Timer = Timer;

})();

