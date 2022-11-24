<script>
	window.tribooScriptModules = [
		{
			countdown: {
				exists: document.getElementsByClassName('slide-countdown-js').length,
				params: {
					url: '/resources/assets/js/components/partials/card-events--big.js',
					key: 'card-events-js',
				},
				require: [  // to write this object follow logic loading
					{
						name: 'countdown-js',
						url: '/resources/assets/js/libs/jquery.countdown.min.js'
					}
				]
			}
		},{
			mainJs: {
				exists: document.getElementsByClassName('main-padelsociale-js').length,
				params: {
					url: '/resources/assets/js/main-padelsociale.js',
					key: 'main-padelsociale-js',
				}
			}
		},{
			forms: {
				exists: document.getElementsByClassName('forms-js').length,
				params: {
					url: '/resources/assets/js/components/partials/forms.js',
					key: 'forms-js',
				}
			}
		},{
			cardsSlider: {
				exists: document.getElementsByClassName('cards-slider-js').length,
				params: {
					url: '/resources/assets/js/components/sections/cards-slider.js',
					key: 'cards-slider-js',
				}
			}
		}
	];
</script>
