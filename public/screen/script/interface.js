export class Interface {
  constructor(controller) {
    this.$pumpToggle = document.querySelector('.pump-toggle');
    this.$slider = document.querySelector('input');
    this.controller = controller;
    this.isPumpWorking = null;

    this.setup();
  }

  setup() {
    this.controller.addEventListener(
      'pump_state_changing',
      (res) => (this.$slider.disabled = true)
    );

    this.controller.addEventListener(
      'current_pump_state',
      ({ data: { speed } }) => {
        if (this.isPumpWorking === null && speed) {
          this.$slider.value = speed;
        }

        this.$slider.disabled = false;
        this.isPumpWorking = !!speed;
      }
    );

    this.$slider.addEventListener('change', () => this.sliderChange());
    this.$pumpToggle.addEventListener('click', () => this.pumpToggle());
  }

  sliderChange() {
    if (this.isPumpWorking) {
      this.controller.send('set_pump', {
        speed: this.$slider.value,
      });
    }
  }

  pumpToggle() {
    this.controller.send('set_pump', {
      speed: this.isPumpWorking ? 0 : this.$slider.value,
    });
  }
}
