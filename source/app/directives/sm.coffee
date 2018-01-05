module.exports = ($rootScope, $timeout)->
	parseDuration = (duration, element)->
		if typeof duration is 'string' and duration.match(/^(\.|\d)*\d+vh$/)
			duration = duration.replace 'vh', '%'
		else
			if typeof duration is 'string' and duration.match(/^(\.|\d)*\d+%$/)
				value = parseFloat( duration ) / 100
				element = if typeof element is 'string' then document.querySelector element else element
				duration = element.offsetHeight * value
			else
				duration = parseFloat duration
		return duration
	createScene = (element, config)->
		triggerElement = config.triggerElement or element[0]
		if typeof config.duration isnt 'undefined'
			durationElement = if typeof config.durationElement isnt 'undefined' then config.durationElement else triggerElement
			config.duration = parseDuration config.duration, durationElement
		else
			config.duration = 0
		if typeof config.offset isnt 'undefined'
			offsetElement = if typeof config.durationElement isnt 'undefined' then config.durationElement else triggerElement
			config.offset = parseDuration config.offset, offsetElement
		else
			config.offset = 0
		reverse = if typeof config.reverse isnt 'undefined' then config.reverse else on
		options = 
			triggerElement : config.triggerElement or element[0]
			triggerHook : config.triggerHook or 0.5
			duration : config.duration
			offset : config.offset
			reverse : reverse
		$rootScope.$on 'sceneDestroy', ->
			scene.destroy() if scene and not config.fixed
			return
		#scene.destroy() if scene
		scene = new ScrollMagic.Scene options
		pseudoRegex = /(:before|:after)/
		if config.pin
			pinEl = config.pin.element || element[0]
			scene.setPin pinEl, config.pin
		if config.tween
			if Array.isArray config.tween
				tweenEl = config.tween[0].element or element[0]
				tweenEl = if typeof tweenEl is 'string' and pseudoRegex.test tweenEl then CSSRulePlugin.getRule tweenEl else tweenEl
				speed = config.tween[0].speed or .5
				tween = TweenMax.fromTo tweenEl, speed, config.tween[0], config.tween[1]
				scene.setTween tween
			else
				tweenEl = config.tween.element or element[0]
				tweenEl = if typeof tweenEl is 'string' and pseudoRegex.test tweenEl then CSSRulePlugin.getRule tweenEl else tweenEl
				speed = config.tween.speed or .5
				tween = TweenMax.to tweenEl, speed, config.tween
				scene.setTween tween
		if config.class
			config.class = {classes: config.class} if typeof config.class isnt 'object'
			classEl = config.class.element or element[0]
			scene.setClassToggle classEl, config.class.classes
		scene.addTo controller
		return
	scrollmagic =
		scope : on
		link : (scope, element, attrs)->
			controller.update on
			config = scope.$eval attrs.ngSm
			if Array.isArray config
				for i in config
					createScene element, i
			else
				createScene element, config
			return
			return