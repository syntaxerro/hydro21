import { Part } from './part.js';

export class Pipe extends Part {
  constructor(props) {
    const [{ x: x1, y: y1 }, { x: x2, y: y2 }] = props;
    super('line', { x1, y1, x2, y2 });
    this.svg.classList.add('pipe');

    this.background = [
      this.createElement('line', { x1, y1, x2, y2 }, 'pipe-back'),
    ];
  }
}
