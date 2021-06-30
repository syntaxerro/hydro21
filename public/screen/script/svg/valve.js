import { Svg } from './svg.js';

export class Valve extends Svg {
  constructor({ x, y, horizontal }) {
    const height = horizontal ? 50 : 100;
    const width = horizontal ? 100 : 50;

    super('rect', { x, y, width, height });
    this.attr({
      fill: 'var(--disabled)',
      cursor: 'pointer',
    });

    this.svg.onclick = () => this.clicked();
    this.enabled = false;

    this.center = { x: x + width / 2, y: y + height / 2 };
  }

  clicked() {
    const event = new Event('setValue');
    event.data = !this.enabled;
    this.dispatchEvent(event);
  }

  setValue(value) {
    this.enabled = value;

    this.attr({
      fill: `var(--${this.enabled ? 'water' : 'disabled'})`,
    });

    this.attached.forEach((pipe) => pipe.setClass(this.enabled));
  }
}
