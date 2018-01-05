module.exports = ($rootScope, $scope, data) => {
	$rootScope.bodyClass = data.bodyClass;
	document.querySelector('title').innerHTML = data.title;
	$scope.content = data.content;
}