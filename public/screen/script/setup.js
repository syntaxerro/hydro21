import { Valve } from './svg/valve';
import { Pipe } from './svg/pipe';

const svg = document.querySelector('svg');

const valvesY = 92;

export const pump = new Valve({ x: 652, y: 302 });

export const valves = {
  main: new Valve({ x: 48, y: 302 }),
  first: new Valve({ x: 48, y: valvesY }),
  second: new Valve({ x: 245, y: valvesY }),
  third: new Valve({ x: 450, y: valvesY }),
  fourth: new Valve({ x: 652, y: valvesY }),
};

const underValvesY = valvesY + 168;
const overValvesY = 0;
const underValvesStart = { x: pump.center.x, y: underValvesY };
const underValvesEnd = { x: valves.first.center.x, y: underValvesY };

export const pipes = [
  new Pipe([valves.main.center, pump.center]),
  new Pipe([pump.center, underValvesStart]),
  new Pipe([underValvesStart, underValvesEnd]),
];

export const valvesBeforePipes = [
  new Pipe([
    { x: valves.fourth.center.x, y: underValvesY },
    valves.fourth.center,
  ]),
  new Pipe([
    { x: valves.third.center.x, y: underValvesY },
    valves.third.center,
  ]),
  new Pipe([
    { x: valves.second.center.x, y: underValvesY },
    valves.second.center,
  ]),
  new Pipe([
    { x: valves.first.center.x, y: underValvesY },
    valves.first.center,
  ]),
];

export const valvesAfterPipes = [
  new Pipe([
    valves.fourth.center,
    { x: valves.fourth.center.x, y: overValvesY },
  ]),
  new Pipe([
    valves.third.center,
    { x: valves.third.center.x, y: overValvesY },
  ]),
  new Pipe([
    valves.second.center,
    { x: valves.second.center.x, y: overValvesY },
  ]),
  new Pipe([
    valves.first.center,
    { x: valves.first.center.x, y: overValvesY },
  ]),
];

[...pipes, ...valvesBeforePipes, ...valvesAfterPipes].forEach((element) =>
  svg.append(element.svg)
);

svg.append(pump.svg);

for (let valve in valves) {
  svg.append(valves[valve].svg);
}
