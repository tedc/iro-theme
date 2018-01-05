module.exports = ()=> {
	return {
		 controller: [ "$scope", "$http", "$timeout", "$httpParamSerializerJQLike", '$window', ($scope, $http, $timeout, $httpParamSerializerJQLike, $window)=> {
		 	$scope.formData = {}
		 	$scope.isContactSent = false;
		 	$scope.isSubmitted = false;
		 	$scope.submit = (isValid)=> {
		 		if($scope.isSubmitted) return;
		 		$scope.isSubmitted = true;
            	let url = vars.wc.form;
            	let frmdata = $scope.formData;
            	let config = {
            		headers : { 
            			"Content-type" : "application/x-www-form-urlencoded; charset=utf-8"
            		}
            	};
            	$scope.formData = {}
         	  	$scope.contactForm.$setUntouched();
          		$scope.contactForm.$setPristine();
          		if(isValid) {
          			$http
          				.post(url, $httpParamSerializerJQLike(frmdata), config)
          				.then( (res)=> {		
          					if(!res) return;
          					if($window.dataLayer) $window.dataLayer.push({event: 'formSubmissionSuccess'});
          					$scope.isContactSent = true;
          					$scope.alert = res.data.formMsg;
          					$scope.formData = {
          						_iro_form_nonce : res.data.new_nonce,
          						_bcc : frmdata._bcc,
          						_send_to : frmdata._send_to
          					};
          					$timeout(()=> {
                                $scope.isSubmitted = false;
                                $scope.isContactSent = false;
          					}, 5000);
          				});
          		}
            }
		 }]
	}
}