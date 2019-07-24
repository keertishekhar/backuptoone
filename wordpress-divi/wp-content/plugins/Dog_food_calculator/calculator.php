<!DOCTYPE html>
<!-- saved from url=(0067)https://tailblazerspets.com/tailblazers-pet-raw-food-calculator.php -->
<html style="" class="js no-touch history boxshadow csstransforms3d csstransitions video svg webkit chrome win js sticky-header-enabled sticky-header-negative wf-leaguegothic-n4-active wf-sourcesanspro-n6-active wf-sourcesanspro-i6-active wf-sourcesanspro-i3-active wf-sourcesanspro-n3-active wf-leaguegothic-i4-active wf-alize-n4-active wf-active sticky-header-active"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style type="text/css">@charset "UTF-8";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\:form{display:block;}.ng-animate-start{border-spacing:1px 1px;-ms-zoom:1.0001;}.ng-animate-active{border-spacing:0px 0px;-ms-zoom:1;}</style>

	<!-- Basic -->
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Calcuate how much raw food to feed your dog or cat on a daily basis. Decimal or fraction.">
<meta name="keywords" content="raw pet food calculator, raw food calculator.">
<title>Simple - Raw Food Calculator</title>
	<meta name="author" content="Tail Blazers Pets">
	<!-- Favicon -->
	<link rel="shortcut icon" href="https://tailblazerspets.com/img/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="https://tailblazerspets.com/img/apple-touch-icon.png">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<style>
	.cc-selector input{
    margin:0;padding:0;
    -webkit-appearance:none;
       -moz-appearance:none;
            appearance:none;
}

.cc-selector-2 input{
    position:absolute;
    z-index:999;
}

.cc-selector-2 input:active +.drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
.cc-selector-2 input:checked +.drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
    -webkit-filter: none;
       -moz-filter: none;
            filter: none;
}
.drinkcard-cc{
    cursor:pointer;
    background-size:contain;
    background-repeat:no-repeat;
    display:inline-block;
    width:100px;height:70px;
    -webkit-transition: all 100ms ease-in;
       -moz-transition: all 100ms ease-in;
            transition: all 100ms ease-in;
    -webkit-filter: brightness(1.8) grayscale(1) opacity(.5);
       -moz-filter: brightness(1.8) grayscale(1) opacity(.5);
            filter: brightness(1.8) grayscale(1) opacity(.5);
}
.drinkcard-cc:hover{
    -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
       -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
            filter: brightness(1.2) grayscale(.5) opacity(.9);
}
.section-contact-area .container{
	text-align:center;
}
.section-contact-area .container h1{
	color:#6f6ff9;
}
.section-contact-area .container h3{
	color:#6f6ff9;
}
.section-contact-area .container hr{
	width: 80%;
	color: #6a6aee;
}
.section-contact-area .form-group{
	margin:auto;
	width:100%;
}
.section-contact-area .form-group .control-label {
	font-size: 40px;
	white-space: nowrap;
	color: #6f6ff9;
	float: left;
	width: 50%;
	text-align:left;
}
.section-contact-area .form-group div{
	margin: 20px 0px;
}
.section-contact-area .container form .alert{
	color:#6f6ff9;
	font-size: 13px; 
	width: 50%;
	margin: auto;
	background:#eee;
	padding:20px;
	margin-bottom:50px;
}
.section-contact-area .form-group .form-control{
	padding: 12px 20px;
	margin: 8px 0;
	display: inline-block;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-sizing: border-box;
}
.section-contact-area .btn{
	background-color: #6f6ff9;
	color: white;
	padding: 14px 20px;
	margin: 8px 0;
	border: none;
	border-radius: 4px;
	cursor: pointer;
}
@media(max-width:768px){
	.section-contact-area .form-group .control-label {
		float:none;
		text-align:center;
		width:100%;
	}
}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.5/angular.min.js"></script>
	<script>
		var app = angular.module("app", []);
		app.controller("myController", function($scope) {
			$scope.calculator = {
			pet : "pet",
			resultDecimalLb : null,
			resultDecimalOz : null,
			resultFractionLb : null,
			resultFractionOz : null,
			resultFractionOzF : null
		// ounce fraction
		};
		var i = 'puppy';
		var chart = {
			puppy : {
				low : 4,
				moderate : 5,
				high : 6
			},
			adultDog : {
				low : 2,
				moderate : 2.5,
				high : 3
			},
			performance : {
				low : 3,
				moderate : 5.5,
				high : 6
			},
			senior : {
				low : 1.5,
				moderate : 1.75,
				high : 2
			},
			kitten : {
				low : 3.75,
				moderate : 5,
				high : 6.25
			},
			adultCat : {
				low : 2,
				moderate : 2.5,
				high : 3
			}
		};

		$scope.calculate = function() {

			var percent = null;
			if ($scope.calculator.pet && ($scope.calculator.dogAge || $scope.calculator.catAge) && $scope.calculator.activity && $scope.calculator.weight) {

				if ($scope.calculator.pet == 'dog') {
					percent = chart[$scope.calculator.dogAge][$scope.calculator.activity];
				} else {
					percent = chart[$scope.calculator.catAge][$scope.calculator.activity];
				}

			}
			// console.log(percent);
			if (percent == null) {
				$scope.calculator.resultDecimalLb = null;
				$scope.calculator.resultDecimalOz = null;
				$scope.calculator.resultFractionLb = null;
				$scope.calculator.resultFractionOz = null;
				$scope.calculator.resultFractionOzF = null;
			} else {
				if ($scope.active == 'Decimal') {
					$scope.calculator.resultFractionLb = null;
					$scope.calculator.resultFractionOz = null;
					$scope.calculator.resultFractionOzF = null;

					var food = poundsToOunces($scope.calculator.weight) * percent / 100;
					food.toFixed(0);
					// console.log('food', food);

					$scope.calculator.resultDecimalLb = Math.floor(food / 16);
					if ($scope.calculator.resultDecimalLb >= 1) {
						$scope.calculator.resultDecimalLb = (food / 16).toFixed(1);
						$scope.calculator.resultDecimalLb = parseFloat($scope.calculator.resultDecimalLb.toString())
						$scope.calculator.resultDecimalOz = 0;
					} else {
						$scope.calculator.resultDecimalOz = food;
					}

				}

				if ($scope.active == 'Fraction') {
					$scope.calculator.resultDecimalLb = null;
					$scope.calculator.resultDecimalOz = null;

					var food = poundsToOunces($scope.calculator.weight) * percent / 100;
					food.toFixed(0);
					$scope.calculator.resultFractionLb = Math.floor(food / 16);

					var remainder = food % 16;
					$scope.calculator.resultFractionOz = Math.floor(remainder);
					$scope.calculator.resultFractionOzF = closest(remainder - $scope.calculator.resultFractionOz);

				}
			}

		}

		var poundsToOunces = function(lbs) {
			return lbs * 16;
		}

		function closest(num) {
			var conversionArray = [ 0, 0.0625, 0.125, 0.1875, 0.25, 0.3125, 0.375, 0.4375, 0.5, 0.5625, 0.625, 0.6875, 0.75, 0.8125, 0.875, 0.9375 ];
			var minDiff = 1000;
			var ans;
			for (i = 0; i < 16; i++) {
				var m = Math.abs(num - conversionArray[i]);
				if (m < minDiff) {
					minDiff = m;
					ans = i;
				}
			}
			// console.log("closest", ans + 1);
			return ans;
		}

		// ----------- toggle Style 2 -----------//
		$scope.active = 'Decimal';
		$scope.setActive = function(type) {
			$scope.active = type;
		};
		$scope.isActive = function(type) {
			return type === $scope.active;
		};

		// --------
		$scope.scrollDown = function() {
			$timeout(function() {
				$ionicScrollDelegate.scrollBottom();
				// console.log('scroll');
			}, 300);
		}
		});
	</script>
</head>

<body ng-app="app" class="ng-scope">

				<div class="section-contact-area" style="margin-top:2%;">
				
					
					<div class="container ng-scope" ng-controller="myController">
									<h1>Raw Food Calculator</h1>
					<h3>Choose your pet type below to start!</h3>
					<hr>
					
	

						<form class="form-horizontal form-bordered ng-valid ng-dirty" style="min-height: 444px;">
							<div class="form-group">
								<label class="col-md-3 col-md-offset-2 control-label" for="inputSuccess">My pet is a:</label>
								<div class="col-md-5">
									<div class="cc-selector">
										<input id="dog" type="radio" value="dog" ng-model="calculator.pet" ng-change="calculate()" class="ng-valid ng-dirty" name="004">
										<label class="drinkcard-cc" style="background-image:url(https://tailblazerspets.com/img/dog_cat.png);" for="dog"></label>
										<input id="cat" type="radio" value="cat" ng-model="calculator.pet" ng-change="calculate()" class="ng-valid ng-dirty" name="005">
										<label class="drinkcard-cc" style="background-image:url(https://tailblazerspets.com/img/cat_cat.png);" for="cat"></label>
									</div>
								</div>
							</div>
							
							<!-- ngIf: calculator.pet=='dog' -->
							<div class="form-group" ng-if="calculator.pet=='dog'">
								<label class="col-md-3 col-md-offset-2 control-label" for="inputSuccess">My dog is a:</label>
								<div class="col-md-5">
									<select class="form-control input-lg mb-md" ng-model="calculator.dogAge" ng-change="calculate()">
										<option value='puppy'>Puppy</option>
										<option value='adultDog'>Adult</option>
										<option value='senior'>Senior</option>
										<option value='performance'>Performance/Extremly Active</option>
									</select>
								</div>
							</div>
							
							<!-- ngIf: calculator.pet=='cat' -->
							<div class="form-group ng-scope" ng-if="calculator.pet==&#39;cat&#39;">
								<label class="col-md-3 col-md-offset-2 control-label" for="inputSuccess">My cat is a:</label>
								<div class="col-md-5">
									<select class="form-control input-lg mb-md ng-valid ng-dirty" ng-model="calculator.catAge" ng-change="calculate()">
										<option value="kitten">Kitten over 8 weeks</option>
										<option value="adultCat">Adult</option>
									</select>
								</div>
							</div><!-- end ngIf: calculator.pet=='cat' -->
							
							<!-- ngIf: calculator.dogAge || calculator.catAge -->
							<div class="form-group" ng-if="calculator.dogAge || calculator.catAge">
								<label class="col-md-3 col-md-offset-2 control-label" for="inputSuccess">My {{calculator.pet}}'s activity level is:</label>
								<div class="col-md-5">
									<div class="cc-selector">
										<input id="low" type="radio" value="low" ng-model="calculator.activity" ng-change="calculate()" class="ng-pristine ng-valid" name="008">
										<label class="drinkcard-cc" for="low" style="background-image:url(https://tailblazerspets.com/img/activity_indicator_low.png);"></label>
										<input id="moderate" type="radio" value="moderate" ng-model="calculator.activity" ng-change="calculate()" class="ng-pristine ng-valid" name="009">
										<label class="drinkcard-cc" style="background-image:url(https://tailblazerspets.com/img/activity_indicator_medium.png);" for="moderate"></label>
										<input id="high" type="radio" value="high" ng-model="calculator.activity" ng-change="calculate()" class="ng-valid ng-dirty" name="00A">
										<label class="drinkcard-cc" style="background-image:url(https://tailblazerspets.com/img/activity_indicator_high.png);" for="high"></label>
									</div>
								</div>
							</div><!-- end ngIf: calculator.dogAge || calculator.catAge -->
							
							<!-- ngIf: calculator.activity -->
							<div class="form-group ng-scope" ng-if="calculator.activity">
								<label class="col-md-3 col-md-offset-2 control-label" for="inputSuccess">My {{calculator.pet}}'s weight is (lbs):</label>
								<div class="col-md-5">
									<input class="form-control input-lg mb-md ng-valid ng-valid-number ng-dirty" type="number" ng-model="calculator.weight" ng-change="calculate();">
								</div>
							</div><!-- end ngIf: calculator.activity -->
							
							<!-- ngIf: calculator.weight -->
							<div ng-if="calculator.weight" style="text-align: center;" class="ng-scope">
								<button type="button" class="btn btn-3d mr-xs mb-sm btn-secondary" ng-class="{&#39;btn-secondary&#39;: isActive(&#39;Decimal&#39;)}" ng-click="setActive(&#39;Decimal&#39;); calculate()">Decimal</button>
								<button type="button" class="btn btn-3d mr-xs mb-sm" ng-class="{&#39;btn-secondary&#39;: isActive(&#39;Fraction&#39;)}" ng-click="setActive(&#39;Fraction&#39;); calculate()">Fraction</button>
							</div><!-- end ngIf: calculator.weight -->
							

							<!-- ngIf: calculator.weight -->
							<div class="alert alert-default col-md-offset-2 col-md-8" ng-if="calculator.weight">
							  <div class="item item-avatar">
								<i class="icon ion-heart card-icon" style="padding-top:3px;"></i>
								<h2 style="overflow: initial;">Your pet should be eating:</h2>
								
								<h2 ng-if="(active=='Decimal')">
								  <b ng-if="calculator.resultDecimalLb > 0">{{calculator.resultDecimalLb}} lb{{calculator.resultDecimalLb > 1 ? 's' : ''}}</b>
								  <b ng-if="calculator.resultDecimalLb > 0 && calculator.resultDecimalOz > 0">and</b>
								  <b ng-if="calculator.resultDecimalOz > 0">{{calculator.resultDecimalOz|number:1}} ounce{{calculator.resultDecimalOz > 1 ? 's' : ''}}</b>
								   of food per DAY
								</h2>
								
								<h2 ng-if="(active=='Fraction')">
								  <b ng-if="calculator.resultFractionLb > 0">{{calculator.resultFractionLb}} lb{{calculator.resultFractionLb > 1 ? 's' : ''}}</b>
								  <b ng-if="calculator.resultFractionLb > 0 && (calculator.resultFractionOz > 0 || calculator.resultFractionOzF > 0)">and</b>
								  <b ng-if="(calculator.resultFractionOz > 0 || calculator.resultFractionOzF > 0)">{{calculator.resultFractionOz>0 ? calculator.resultFractionOz : ''}} {{calculator.resultFractionOzF>0 ? calculator.resultFractionOzF+"/16 of an ounce" : "ounce"+(calculator.resultFractionOz>1 ? 's' : '') }}</b>
								   of food per DAY
								</h2>
								
								<p style="white-space: normal; font-style: italic;">
								  (due to individual variances in pet metabolism and activity levels, this number should be used as a guideline only)
								</p>
							  </div>
							</div><!-- end ngIf: calculator.weight -->
							
						</form>
						
						

					</div>

				</div>	
								
							</div>



</body></html>