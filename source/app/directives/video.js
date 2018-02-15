module.exports = ($rootScope, $timeout)=> {
    return {
        scope: true,
        link : (scope, element)=> {
            console.log(element);
            if(vars.main.mobile) {
                scope.isLoading = false;
                scope.isPaused = true;
                scope.isLoaded = false
                var canvas = null;
                let paintVideo = ()=> {
                    if(canvas === null) {
                        canvas = document.createElement("canvas");
                        canvas.width = element[0].videoWidth;
                        canvas.height = element[0].videoHeight;
                        element.after(canvas)
                    }
                    canvas.getContext('2d').drawImage(element[0], 0, 0, canvas.width, canvas.height);
                    $timeout(()=> {
                        if(scope.isLoading && !scope.isLoaded) scope.isLoading = false;
                        scope.isLoaded = true;
                    });
                    if( !element[0].paused ) requestAnimationFrame( paintVideo );
                }
                element.on('pause', ()=> {
                    $timeout(()=> {
                        scope.isPaused = true;
                    })
                }) ;
                element.on( 'playing', paintVideo );
            }
            scope.play = ()=>{
                if (!scope.isLoaded) scope.isLoading = true;
                if(element[0].paused) {
                    element[0].play();
                } else {
                    element[0].pause();
                }
                scope.isPaused = element[0].paused;
            }
            if(vars.main.mobile) return;
            let tween = TweenMax.to({index:0}, 5, {
                index: 10,
                onUpdateParams : ['{self}'],
                onUpdate : (evt)=> {
                    console.log(evt.target.index);
                    // if(evt.target.index > 0 && evt.target.index <= 9.6) {
                    //     element[0].play();
                    // } else {
                    //     element[0].pause();
                    // }
                }
            });
            let enterVideoScene = new ScrollMagic.Scene({
                triggerElement : element[0],
                duration: '100%'
            }).setTween(tween).addTo(controller);
            element.on( '$destroy', ()=> {
                enterVideoScene.destroy();
            });
        }
    }
}