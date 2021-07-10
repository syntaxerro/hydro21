import { Svg } from './svg.js';

export class Part extends Svg {
  constructor(name, props) {
    super(name, props);
    this.pump = 0;
  }

  updateValue(value) {
    const { enabled, pump } = value;

    if (enabled !== undefined) {
      this.enabled = enabled;
    }

    if (pump !== undefined) {
      this.pump = pump;
    }

    this.update();
  }

  update() {
    if (this.pump) {
      this.svg.classList.add('flowing');
    } else {
      this.svg.classList.remove('flowing');
    }

    if (this.enabled) {
      this.svg.classList.add('enabled');
    } else {
      this.svg.classList.remove('enabled');
    }
  }
}
