import { Part } from './part.js';

export class Pump extends Part {
  constructor({ x, y }) {
    const height = 50;
    const width = 50;

    super('rect', { x, y, width, height });
    this.svg.classList.add('pump');

    this.attr({
      fill: 'var(--disabled)',
    });

    this.center = { x: x + width / 2, y: y + height / 2 };
  }

  updateValue(value) {
    const { pump } = value;
    const enabled = pump > 0;

    if (pump !== undefined) {
      this.enabled = enabled;
      this.pump = pump;
    }

    this.update();
  }
}
