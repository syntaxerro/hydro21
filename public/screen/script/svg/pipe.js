import { Svg } from './svg.js';

export class Pipe extends Svg {
  constructor(props) {
    const [{ x: x1, y: y1 }, { x: x2, y: y2 }] = props;
    super('line', { x1, y1, x2, y2 });
    this.svg.classList.add('pipe');

    this.attr({
      stroke: 'var(--water)',
      'stroke-dasharray': 12,
      'stroke-dashoffset': 0,
      'stroke-width': 8,
      'stroke-linecap': 'round',
    });
  }

  toggle(enabled) {
    if (enabled) {
      this.svg.classList.add('pipe-flowing');
    } else {
      this.svg.classList.remove('pipe-flowing');
    }

    this.attached.forEach((pipe) => pipe.toggle(enabled));
  }
}
