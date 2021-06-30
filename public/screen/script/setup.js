import { Valve } from './svg/valve.js';
import { Pipe } from './svg/pipe.js';

const svg = document.querySelector('svg');

const valvesY = 92;

export const pump = new Valve({ x: 652, y: 302 });

export const valves = {
  main: new Valve({ x: 48, y: 327, horizontal: true }),
  ch1: new Valve({ x: 48, y: valvesY }),
  ch2: new Valve({ x: 245, y: valvesY }),
  ch3: new Valve({ x: 450, y: valvesY }),
  ch4: new Valve({ x: 652, y: valvesY }),
};

const underValvesY = valvesY + 168;
const overValvesY = 0;
const underValvesStart = { x: pump.center.x, y: underValvesY };
const underValvesEnd = { x: valves.ch1.center.x, y: underValvesY };

export const pipes = [
  new Pipe([valves.main.center, pump.center]),
  new Pipe([pump.center, underValvesStart]),
  new Pipe([underValvesStart, underValvesEnd]),
];

export const valvesBeforePipes = [
  new Pipe([
    { x: valves.ch4.center.x, y: underValvesY },
    valves.ch4.center,
  ]),
  new Pipe([
    { x: valves.ch3.center.x, y: underValvesY },
    valves.ch3.center,
  ]),
  new Pipe([
    { x: valves.ch2.center.x, y: underValvesY },
    valves.ch2.center,
  ]),
  new Pipe([
    { x: valves.ch1.center.x, y: underValvesY },
    valves.ch1.center,
  ]),
];

export const valvesAfterPipes = [
  new Pipe([
    valves.ch4.center,
    { x: valves.ch4.center.x, y: overValvesY },
  ]),
  new Pipe([
    valves.ch3.center,
    { x: valves.ch3.center.x, y: overValvesY },
  ]),
  new Pipe([
    valves.ch2.center,
    { x: valves.ch2.center.x, y: overValvesY },
  ]),
  new Pipe([
    valves.ch1.center,
    { x: valves.ch1.center.x, y: overValvesY },
  ]),
];

[...pipes, ...valvesBeforePipes, ...valvesAfterPipes].forEach((element) =>
  svg.append(element.svg)
);

svg.append(pump.svg);

for (let valve in valves) {
  svg.append(valves[valve].svg);
}
