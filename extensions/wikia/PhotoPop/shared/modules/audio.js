var exports = exports || {};

define.call(exports, [
			'modules/data',
			'modules/settings'
		],

	function(data, settings){
		var sounds = {},
		mute = false,
		isApp = Wikia.Platform.is('app'),
		prefix = ((isApp) ? '' : "extensions/wikia/PhotoPop/") + "shared/audio/",
		path,
		audioFiles = settings.sounds;

		function getMute(){
			return mute;
		}

		data.storage.addEventListener({name: "get", key: "mute"}, function(event) {
			mute = event.value || false;
		});
		data.storage.get('mute');

		for(var p in audioFiles){
			path = prefix + audioFiles[p] + ".mp3";
			sounds[p] = (isApp) ? path : new Audio(path);
		}

		return {
			play: function(sound) {
				if(isApp)
					Titanium.App.fireEvent('sounds:play', {sound: sounds[sound], mute: getMute()});
				else{
					if(sound == 'win' || sound == 'fail'){
						for(var p in sounds){
							sounds[p].currentTime = 0;
							sounds[p].pause();
						}
					}
					sounds[sound].play();
				}
			},

			setMute: function(flag) {
				mute = flag;

				for(var sound in sounds){
					sounds[sound].muted = mute;
				}

				data.storage.set('mute', mute);
				return mute;
			},

			getMute: function(){
				return mute;
			},

			toggleMute: function(){
				return this.setMute(!this.getMute());
			}
		};
	}
);