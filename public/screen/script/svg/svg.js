export class Svg {
  constructor(name, props) {
    this.svg = document.createElementNS('http://www.w3.org/2000/svg', name);
    this.attr(props);
    this.attached = [];
  }

  attach(...elements) {
    elements.forEach((element) => {
      this.attached.push(element);
    });
  }

  attr(props) {
    for (let attr in props) {
      this.svg.setAttribute(attr, props[attr]);
    }
  }
}
