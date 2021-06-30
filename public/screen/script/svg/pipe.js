import { Svg } from './svg.js';

export class Pipe extends Svg {
  constructor(props) {
    const [{ x: x1, y: y1 }, { x: x2, y: y2 }] = props;
    super('line', { x1, y1, x2, y2 });
    this.enabledClass = 'pipe-flowing';
    this.svg.classList.add('pipe');

    // this.pipe = this.createElement('line', { x1, y1, x2, y2 });
    // this.pipe.classList.add('pipe-back');
  }
}
