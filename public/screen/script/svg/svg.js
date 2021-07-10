const svg = document.querySelector('svg');

export class Svg extends EventTarget {
  constructor(name, props) {
    super();
    this.svg = this.createElement(name, props);
  }

  append() {
    svg.append(this.svg);
  }

  createElement(name, props) {
    const element = document.createElementNS(
      'http://www.w3.org/2000/svg',
      name
    );
    this.attr(props, element);
    return element;
  }

  attr(props, element = this.svg) {
    for (let attr in props) {
      element.setAttribute(attr, props[attr]);
    }
  }
}
