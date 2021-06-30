import { Svg } from './svg.js';

export class Valve extends Svg {
  constructor({ x, y }) {
    const height = 100;
    const width = 100;

    super('rect', { x, y, width, height });
    this.attr({
      transform: `rotate(45,${x + width / 2},${y + width / 2})`,
      fill: 'var(--disabled)',
      cursor: 'pointer',
    });

    this.svg.onclick = () => this.toggle();
    this.enabled = false;

    this.center = { x: x + width / 2, y: y + height / 2 };
  }

  toggle() {
    this.enabled = !this.enabled;
    this.attr({
      fill: `var(--${this.enabled ? 'water' : 'disabled'})`,
    });

    this.attached.forEach((pipe) => pipe.toggle(this.enabled));
  }
}
