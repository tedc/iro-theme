module.exports = ()=> {
	return {
		controller: [ "$scope", "$http", "$timeout", "$httpParamSerializerJQLike", '$window', '$attrs', '$rootScope', ($scope, $http, $timeout, $httpParamSerializerJQLike, $window, $attrs, $rootScope)=> {
		 	$scope.formData = {}
      $rootScope.isSizeForm = false;
		 	$scope.isContactSent = false;
		 	$scope.isSubmitted = false;
      let is_size_form = $attrs.formKind !== 'undefined' && $attrs.formKind == 'size' ? true : false;
		 	$scope.submit = (isValid)=> {
		 		if($scope.isSubmitted) return;
		 		$scope.isSubmitted = true;
      	let url = (is_size_form) ? vars.wc.size_form : vars.wc.form;
      	let frmdata = $scope.formData;
      	let config = {
      		headers : { 
      			"Content-type" : "application/x-www-form-urlencoded; charset=utf-8"
      		}
      	};
        $rootScope.$watch('isSizeForm', (newValue, oldValue)=> {
          if(newValue=!oldValue) {
            $timeout(()=> {
              $rootScope.$broadcast('update_scrollbar');
            }, 500);
          }
        })
      	$scope.formData = {}
        if(is_size_form) {
          $scope.sizeForm.$setUntouched();
          $scope.sizeForm.$setPristine();
        } else {
          $scope.contactForm.$setUntouched();
          $scope.contactForm.$setPristine();
        }
    		if(isValid) {
    			$http
    				.post(url, $httpParamSerializerJQLike(frmdata), config)
    				.then( (res)=> {		
    					if(!res) return;
    					if($window.dataLayer) $window.dataLayer.push({event: 'formSubmissionSuccess'});
    					$scope.isContactSent = true;
    					$scope.alert = res.data.formMsg;
              if(is_size_form) {
                $scope.formData = {
                  _bcc : frmdata._bcc,
                  _send_to : frmdata._send_to
                }; 
              } else {
                 $scope.formData = {
                  _iro_form_nonce : res.data.new_nonce,
                  _bcc : frmdata._bcc,
                  _send_to : frmdata._send_to
                }; 
              }
    					
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