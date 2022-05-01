// rel-controlled or dcm-controlled
const PUMP_TYPE = 'rel-controlled';

let startHydratingButton = document.querySelector('#startHydratingButton');
let pumpPowerInput = document.querySelector('#pump-power');
let pumpPowerLabel = document.getElementById('pump-power-label');
let pump = document.querySelector('#pump');

if(PUMP_TYPE === 'rel-controlled') {
    startHydratingButton.removeAttribute('disabled');
    pumpPowerInput.style.display = 'none';
    pumpPowerLabel.style.display = 'none';
}


pumpPowerInput.oninput = changePumpPowerLables;
pumpPowerInput.onchange = function() {
    if(startHydratingButton.dataset.state == 1) {
        socket.send(JSON.stringify({
            "controller": "set_pump",
            "speed": this.value
        }));
    }

};

startHydratingButton.onclick = function() {
    if(this.disabled) {
        return;
    }

    let pumpSpeedValue = PUMP_TYPE === 'dcm-controlled' ? pumpPowerInput.value : 10;

    socket.send(JSON.stringify({
        "controller": "set_pump",
        "speed": startHydratingButton.dataset.state == 1 ? 0 : pumpSpeedValue
    }));
};

function setValveState(valveId, state)
{
    let valve = document.getElementById(valveId);
    valve.dataset.state = (state ? 1 : 0).toString();

    let image = document.querySelector('#'+valveId+' .valve');
    if(state == 1) {
        image.classList.remove('valve-closed');
        image.classList.add('valve-open');
    } else {
        image.classList.remove('valve-open');
        image.classList.add('valve-closed');
    }

    let label = document.querySelector('#'+valveId+' .full-width-center:last-child');
    if(label) {
        label.innerText = state == 1 ? 'OTWARTY' : 'ZAMKNIĘTY';
    }
}

function changePumpPowerLables()
{
    console.log('Ustawiono wydajność pompy: '+pumpPowerInput.value);
    pumpPowerLabel.innerText = (pumpPowerInput.value ? pumpPowerInput.value : '0')+' litrów / minute';

    if(PUMP_TYPE === 'dcm-controlled') {
        pumpPowerInput.value == 0 ? startHydratingButton.setAttribute('disabled', 'disabled') : startHydratingButton.removeAttribute('disabled');
    }
}

let pumpChangingInterval = null;
function startPumpChanging()
{
    startHydratingButton.setAttribute('disabled', 'disabled');
    pumpPowerInput.setAttribute('disabled', 'disabled');
    pumpPowerLabel.innerText = 'Trwa zmiana wydajności pompy...';

    let toggle = false;
    if(pumpChangingInterval === null) {
        pumpChangingInterval = setInterval(function() {
            if(toggle) {
                pump.classList.remove('pump-enabled');
                pump.classList.add('pump-disabled');
            } else {
                pump.classList.remove('pump-disabled');
                pump.classList.add('pump-enabled');
            }
            toggle = !toggle;
        }, 250);
    }
}

function stopPumpChanging(state)
{
    clearInterval(pumpChangingInterval);
    pumpChangingInterval = null;
    pumpPowerInput.removeAttribute('disabled');

    if(state) {
        pump.classList.remove('pump-disabled');
        pump.classList.add('pump-enabled');
    } else {
        pump.classList.remove('pump-enabled');
        pump.classList.add('pump-disabled');
    }
}

var socket = new WebSocket(location.href.replace('http', 'ws').replace(':8075', ':3181').slice(0, -1));
socket.onopen = function (event) {
    socket.onmessage = function(msg) {
        let request = JSON.parse(msg.data);

        if(request.controller === 'current_valves_states') {
            setValveState('ch1-valve', request.ch1);
            setValveState('ch2-valve', request.ch2);
            setValveState('ch3-valve', request.ch3);
            setValveState('ch4-valve', request.ch4);
        }

        if(request.controller === 'current_pump_state') {

            if(request.speed == 0) {
                pump.classList.remove('pump-enabled');
                pump.classList.add('pump-disabled');
                startHydratingButton.innerText = 'ROZPOCZNIJ NAWADNIANIE';
                startHydratingButton.style.background = 'green';
                startHydratingButton.dataset.state = 0;
                if(PUMP_TYPE === 'rel-controlled') {
                    startHydratingButton.removeAttribute('disabled');
                }
                stopPumpChanging(false);
            } else {
                pump.classList.remove('pump-disabled');
                pump.classList.add('pump-enabled');
                startHydratingButton.innerText = 'ZAKOŃCZ NAWADNIANIE';
                startHydratingButton.style.background = 'darkred';
                startHydratingButton.dataset.state = 1;
                startHydratingButton.removeAttribute('disabled');
                stopPumpChanging(true);
            }


            pumpPowerInput.value = request.speed;
            changePumpPowerLables();

        }

        if(request.controller === 'pump_state_changing') {
            startPumpChanging();
        }

        if(request.controller === 'system_status') {
            document.querySelector('#datetime').innerText = request.datetime;
            document.querySelector('#ipAddress').innerText = request.ip;
            document.querySelector('#ssid').innerText = request.wifiSSID;
        }
    };

    let valves = document.querySelectorAll('.valve-button');
    for(let i=0; i<valves.length; i++) {
        let valve = valves[i];

        valve.onclick = function() {
            let allOthersIsDisabled = true;
            for(let valve in {'ch1-valve': null, 'ch2-valve': null, 'ch3-valve': null, 'ch4-valve': null}) {
                if(valve !== this.id) {
                    if(Number(document.getElementById(valve).dataset.state)) allOthersIsDisabled = false;
                }
            }

            if(
                allOthersIsDisabled &&
                Number(this.dataset.state) &&
                Number(document.getElementById('startHydratingButton').dataset.state)
            ) {
                alert('Nie można wyłączyć wszystkich zaworów podczas pracy pompy! Taka konfiguracja grozi uszkodzeniem modułu hydrualicznego.');
                return;
            }

            socket.send(JSON.stringify({
                'controller': 'set_valve',
                'valve': this.id.replace('-valve', ''),
                'state': !Number(this.dataset.state)
            }))
        };
    }

    socket.send(JSON.stringify({
        'controller': 'register_client'
    }));
};

