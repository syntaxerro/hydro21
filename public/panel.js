function qs(selector) {
    return document.querySelector(selector);
}

function qsa_onclick(selector, onclick) {
    let els = document.querySelectorAll(selector);
    for(let i=0; i<els.length; i++) {
        els[i].onclick = onclick;
    }
}

document.querySelector('#controler-frame').setAttribute('src', 'http://'+location.host+':8075');
if(!location.hash) {
    location.hash = '#home';
}
qs('.nav-link[data-bs-target="'+location.hash+'"]').click();
qsa_onclick('.nav-link', function() {
    location.hash = this.dataset.bsTarget;
});

function request(method, controller, action, data, success) {
    let request = new XMLHttpRequest();
    request.open(method, '/api.php?ctrl='+controller+'&action='+action, true);
    request.setRequestHeader("Content-Type", 'application/json');
    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            let resp = this.response;
            success(JSON.parse(resp));
        }
    };

    request.send(JSON.stringify(data));
}

// Harmonogram zadań
qs('#new-schedule-form').onsubmit = function() {
   request('POST', 'SchedulerController', 'create', Object.fromEntries(new FormData(this)), function() {
       location.reload();
   }, 'multipart/form-data') ;

   return false;
};

function removeScheduledTask(id) {
    request('POST', 'SchedulerController', 'remove', {id: id}, function() {
       location.reload();
    }.bind(this));
}

function loadSchedulerGrid() {
    let table = qs('#schedules-grid tbody');
    table.innerHTML = '';

    request('GET', 'SchedulerController', 'grid', {}, function (response) {
        for (let i = 0; i < response.length; i++) {
            let item = response[i];

            let id = '<td>' + item.id + '</td>';
            let dayOfWeek = '<td>' + item.dayOfWeek + '</td>';
            let startTime = '<td>' + item.startTime + '</td>';
            let duration = '<td>' + item.duration + '</td>';

            let sections = [];
            if (item.ch1) {
                sections.push('A');
            }
            if (item.ch2) {
                sections.push('B');
            }
            if (item.ch3) {
                sections.push('C');
            }
            if (item.ch4) {
                sections.push('D');
            }
            sections = '<td>' + sections.join(' ') + '</td>';

            let pumpSpeed = '<td>' + item.pumpSpeed + '</td>';

            let deleteButton = '<button class="btn btn-sm btn-danger" onclick="removeScheduledTask(' + item.id + ')">Usuń</button>';
            let options = '<td>' + deleteButton + '</td>';

            table.innerHTML += '<tr>' + id + dayOfWeek + startTime + duration + sections + pumpSpeed + options + '</tr>';
        }
    });
}
loadSchedulerGrid();

// Historia
(function() {
    let page = 0;
    function loadHistoryGrid() {
        let table = qs('#history-grid tbody');

        request('POST', 'HistoryController', 'grid', {page: page}, function (response) {
            for (let i = 0; i < response.length; i++) {
                let item = response[i];

                let id = '<td>' + item.id + '</td>';
                let datetime = '<td>' + item.datetime + '</td>';
                let ch1 = '<td style="background: '+(item.ch1 === 'ON' ? 'darkgreen' : 'red')+'; color: white">' + item.ch1 + '</td>';
                let ch2 = '<td style="background: '+(item.ch2 === 'ON' ? 'darkgreen' : 'red')+'; color: white">' + item.ch2 + '</td>';
                let ch3 = '<td style="background: '+(item.ch3 === 'ON' ? 'darkgreen' : 'red')+'; color: white">' + item.ch3 + '</td>';
                let ch4 = '<td style="background: '+(item.ch4 === 'ON' ? 'darkgreen' : 'red')+'; color: white">' + item.ch4 + '</td>';
                let pumpSpeed = '<td>' + item.pumpSpeed + '</td>';


                table.innerHTML += '<tr>' + id + datetime + ch1 + ch2 + ch3 + ch4 + pumpSpeed + '</tr>';
            }
        });
    }
    loadHistoryGrid();
    qs('#history-next-button').onclick = function() {
        page++;
        loadHistoryGrid();
    };
})();

// Statystyki
(function() {
    let page = 0;
    function loadStatsGrid() {
        let table = qs('#stats-grid tbody');

        request('POST', 'StatsTableController', 'get', {page: page}, function (response) {
            for (let i = 0; i < response.length; i++) {
                let item = response[i];

                let id = '<td>' + item.id + '</td>';
                let datetime = '<td>' + item.date + '</td>';
                let ch1 = '<td>' + item.ch1 + '</td>';
                let ch2 = '<td>' + item.ch2 + '</td>';
                let ch3 = '<td>' + item.ch3 + '</td>';
                let ch4 = '<td>' + item.ch4 + '</td>';
                let interval = '<td>' + item.interval + '</td>';


                table.innerHTML += '<tr>' + id + datetime + interval + ch1 + ch2 + ch3 + ch4 + '</tr>';
            }
        });
    }
    loadStatsGrid();
    qs('#stats-next-button').onclick = function() {
        page++;
        loadStatsGrid();
    };
})();
