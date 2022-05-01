import { Part } from './part.js';

export class Valve extends Part {
  constructor({ x, y, horizontal }) {
    const height = 14;
    const width = 100;
    super('rect', { x, y, width, height });

    const transform = horizontal
      ? ''
      : `rotate(90, ${x + width / 2}, ${y + height / 2})`;

    this.attr({
      transform,
      fill: 'var(--disabled)',
      cursor: 'pointer',
    });

    this.svg.onclick = () => this.clicked();
    this.enabled = false;

    this.center = { x: x + width / 2, y: y + height / 2 };
    this.position = { x, y };

    const valveEnd = {
      width: 10,
      height: height + 30,
      transform,
      y: y - height,
    };

    this.foreground = [
      this.createElement(
        'rect',
        { x, ...valveEnd },
        'valve-end'
      ),

      this.createElement(
        'rect',
        { x: x + width, ...valveEnd },
        'valve-end'
      ),
    ];
  }

  clicked() {
    const event = new Event('setValue');
    event.data = !this.enabled;
    this.dispatchEvent(event);
  }
}
