const svg = document.querySelector('svg');

export class Svg extends EventTarget {
  constructor(name, props) {
    super();
    this.svg = this.createElement(name, props);
    this.background = [];
    this.foreground = [];
  }

  append() {
    svg.append(this.svg);
    this.foreground.forEach(element => svg.append(element));
  }

  appendBackground() {
    this.background.forEach(element => svg.append(element));
  }

  createElement(name, props, className) {
    const element = document.createElementNS(
      'http://www.w3.org/2000/svg',
      name
    );
    element.classList.add(className);
    this.attr(props, element);

    return element;
  }

  attr(props, element = this.svg) {
    for (let attr in props) {
      element.setAttribute(attr, props[attr]);
    }
  }
}
