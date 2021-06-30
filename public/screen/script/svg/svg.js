export class Svg extends EventTarget {
  constructor(name, props) {
    super();
    this.svg = this.createElement(name, props);
    this.attached = [];
  }

  createElement(name, props) {
    const element = document.createElementNS('http://www.w3.org/2000/svg', name);
    this.attr(props, element);
    

    return element;
  }

  attach(...elements) {
    elements.forEach((element) => {
      this.attached.push(element);
    });
  }

  attr(props, element = this.svg) {
    for (let attr in props) {
      element.setAttribute(attr, props[attr]);
    }
  }

  setClass(enabled) {
    if (enabled) {
      this.svg.classList.add(this.enabledClass);
    } else {
      this.svg.classList.remove(this.enabledClass);
    }

    this.attached.forEach((element) => element.setClass(enabled));
  }

}
