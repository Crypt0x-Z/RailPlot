const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
requestAnimationFrame(update);
const mouse = { x: 0, y: 0, button: false, wheel: 0, lastX: 0, lastY: 0, drag: false, downX: 0, downY: 0 };
const gridLimit = 64;
const gridSize = 128;
const scaleRate = 1.02;
const topLeft = { x: 0, y: 0 };
let stations = [];
let newStationWorldCoord = null;
let selectedStation = null;

let lines = [];

document.getElementById('saveLine').addEventListener('click', function () {
    const name = document.getElementById('lineName').value.trim();
    const code = document.getElementById('lineCode').value.trim();
    const color = document.getElementById('lineColor').value;
    const lineType = document.querySelector('input[name="lineType"]:checked');

    if (!name || !code || !lineType) {
        alert("Line name, code, and type are required.");
        return;
    }

    lines.push({ name: name, code: code, color: color, type: lineType.value, stations: [] });

    // Clear input fields after saving
    document.getElementById('lineName').value = '';
    document.getElementById('lineCode').value = '';
    document.getElementById('lineColor').value = '#000000';
    document.querySelectorAll('input[name="lineType"]').forEach(radio => radio.checked = false);

    // Optionally, close the dropdown after saving
    const dropdownButton = document.querySelector('.dropdown-toggle');
    if (dropdownButton.classList.contains('show')) {
        dropdownButton.click();
    }

    document.getElementById("linesNum").innerHTML = lines.length
});

function clearGrid() {
    stations.length = 0; // Clear the stations array
    document.getElementById("stationsNum").innerHTML = stations.length;
}

function mouseEvents(e) {
    const bounds = canvas.getBoundingClientRect();
    mouse.x = e.pageX - bounds.left - scrollX;
    mouse.y = e.pageY - bounds.top - scrollY;
    if (e.type === "mousedown") {
        mouse.button = true;
        mouse.downX = mouse.x;
        mouse.downY = mouse.y;
    } else if (e.type === "mouseup") {
        mouse.button = false;
    }
    if (e.type === "wheel") {
        mouse.wheel += -e.deltaY;
        e.preventDefault();
    }
}

["mousedown", "mouseup", "mousemove"].forEach(name => document.addEventListener(name, mouseEvents));
document.addEventListener("wheel", mouseEvents, { passive: false });

const panZoom = {
    x: 0,
    y: 0,
    scale: 1,
    apply() { ctx.setTransform(this.scale, 0, 0, this.scale, this.x, this.y) },
    scaleAt(x, y, sc) {
        this.scale *= sc;
        this.x = x - (x - this.x) * sc;
        this.y = y - (y - this.y) * sc;
    },
    toWorld(x, y, point = {}) {
        const inv = 1 / this.scale;
        point.x = (x - this.x) * inv;
        point.y = (y - this.y) * inv;
        return point;
    },
}

function drawGrid(gridScreenSize = 128, adaptive = true) {
    var scale, gridScale, size, x, y, limitedGrid = false;
    if (adaptive) {
        scale = 1 / panZoom.scale;
        gridScale = 2 ** (Math.log2(gridScreenSize * scale) | 0);
        size = Math.max(w, h) * scale + gridScale * 2;
        x = ((-panZoom.x * scale - gridScale) / gridScale | 0) * gridScale;
        y = ((-panZoom.y * scale - gridScale) / gridScale | 0) * gridScale;
    } else {
        gridScale = gridScreenSize;
        size = Math.max(w, h) / panZoom.scale + gridScale * 2;
        panZoom.toWorld(0, 0, topLeft);
        x = Math.floor(topLeft.x / gridScale) * gridScale;
        y = Math.floor(topLeft.y / gridScale) * gridScale;
        if (size / gridScale > gridLimit) {
            size = gridScale * gridLimit;
            limitedGrid = true;
        }
    }
    panZoom.apply();
    ctx.lineWidth = 1;
    ctx.strokeStyle = "#000";
    ctx.beginPath();
    for (i = 0; i < size; i += gridScale) {
        ctx.moveTo(x + i, y);
        ctx.lineTo(x + i, y + size);
        ctx.moveTo(x, y + i);
        ctx.lineTo(x + size, y + i);
    }
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.stroke();
}

function drawPoint(x, y) {
    const worldCoord = panZoom.toWorld(x, y);
    panZoom.apply();
    ctx.lineWidth = 1;
    ctx.strokeStyle = "red";
    ctx.beginPath();
    ctx.moveTo(worldCoord.x - 10, worldCoord.y);
    ctx.lineTo(worldCoord.x + 10, worldCoord.y);
    ctx.moveTo(worldCoord.x, worldCoord.y - 10);
    ctx.lineTo(worldCoord.x, worldCoord.y + 10);
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.stroke();
}

function drawStation(station) {
    panZoom.apply();
    ctx.lineWidth = 2;
    let color = "black";
    if (station.types.includes("underground")) {
        color = "red";
    } else if (station.types.includes("ground")) {
        color = "green";
    } else if (station.types.includes("suspended")) {
        color = "blue";
    }
    ctx.strokeStyle = color;
    ctx.beginPath();
    ctx.arc(station.x, station.y, 5, 0, 2 * Math.PI);
    ctx.fill();
    ctx.stroke();

    // Display station name
    ctx.fillStyle = "black";
    ctx.font = "12px Arial";
    ctx.textAlign = "center";
    ctx.fillText(station.name, station.x, station.y + 20);

    ctx.setTransform(1, 0, 0, 1, 0, 0);
}

var w = canvas.width;
var h = canvas.height;

function update() {
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.globalAlpha = 1;
    if (w !== innerWidth || h !== innerHeight) {
        w = canvas.width = innerWidth;
        h = canvas.height = innerHeight - 50;
    } else {
        ctx.clearRect(0, 0, w, h);
    }
    if (mouse.wheel !== 0) {
        let scale = 1;
        scale = mouse.wheel < 0 ? 1 / scaleRate : scaleRate;
        mouse.wheel *= 0.8;
        if (Math.abs(mouse.wheel) < 1) {
            mouse.wheel = 0;
        }
        panZoom.scaleAt(mouse.x, mouse.y, scale);
    }
    if (mouse.button) {
        if (!mouse.drag) {
            mouse.lastX = mouse.x;
            mouse.lastY = mouse.y;
            mouse.drag = true;
        } else {
            panZoom.x += mouse.x - mouse.lastX;
            panZoom.y += mouse.y - mouse.lastY;
            mouse.lastX = mouse.x;
            mouse.lastY = mouse.y;
        }
    } else if (mouse.drag) {
        mouse.drag = false;
    }
    drawGrid(gridSize, true);
    drawPoint(mouse.x, mouse.y);

    stations.forEach(station => {
        drawStation(station);
    });

    requestAnimationFrame(update);
}

function refocusCanvas() {
const centerX = w / 2;
const centerY = h / 2;
panZoom.x = centerX;
panZoom.y = centerY;
panZoom.scale = 1;
panZoom.apply();
}

canvas.addEventListener('click', function (event) {
    const distance = Math.sqrt((mouse.x - mouse.downX) ** 2 + (mouse.y - mouse.downY) ** 2);
    if (distance > 5) return;
    const worldCoord = panZoom.toWorld(event.offsetX, event.offsetY);
    selectedStation = stations.find(station => Math.sqrt((station.x - worldCoord.x) ** 2 + (station.y - worldCoord.y) ** 2) < 10);
    if (selectedStation) {
        document.getElementById('modalTitle').textContent = "Edit Station";
        document.getElementById('stationName').value = selectedStation.name;
        document.getElementById('undergroundType').checked = selectedStation.types.includes("underground");
        document.getElementById('groundType').checked = selectedStation.types.includes("ground");
        document.getElementById('suspendedType').checked = selectedStation.types.includes("suspended");
        document.getElementById('deleteStation').style.display = 'block'; // Show delete button
    } else {
        document.getElementById('modalTitle').textContent = "Add Station";
        document.getElementById('stationName').value = "";
        document.getElementById('undergroundType').checked = false;
        document.getElementById('groundType').checked = false;
        document.getElementById('suspendedType').checked = false;
        document.getElementById('deleteStation').style.display = 'none'; // Hide delete button
        document.getElementById("stationsNum").innerHTML = stations.length;
    }
    newStationWorldCoord = worldCoord;
    var stationModal = new bootstrap.Modal(document.getElementById('stationModal'));
    stationModal.show();
});

document.getElementById('saveStation').addEventListener('click', function () {
    const name = document.getElementById('stationName').value.trim();
    if (!name) {
        alert("Station name is required.");
        return;
    }

    const types = [];
    if (document.getElementById('undergroundType').checked) types.push("underground");
    if (document.getElementById('groundType').checked) types.push("ground");
    if (document.getElementById('suspendedType').checked) types.push("suspended");

    if (types.length === 0) {
        alert("At least one station type must be selected.");
        return;
    }

    if (selectedStation) {
        if (stations.some(station => station.name === name && station !== selectedStation)) {
            alert("Station name must be unique.");
            return;
        }
        selectedStation.name = name;
        selectedStation.types = types;
    } else {
        if (stations.some(station => station.name === name)) {
            alert("Station name must be unique.");
            return;
        }
        stations.push({
            x: newStationWorldCoord.x, y: newStationWorldCoord.y,
            name: name,
            types: types
        });

        document.getElementById('stationsNum').innerHTML = stations.length;
    }

    selectedStation = null;
    newStationWorldCoord = null;
    var stationModal = bootstrap.Modal.getInstance(document.getElementById('stationModal'));
    stationModal.hide();
});

document.getElementById('deleteStation').addEventListener('click', function () {
    if (selectedStation) {
        stations = stations.filter(station => station !== selectedStation);
        selectedStation = null;
        newStationWorldCoord = null;
        document.getElementById('stationsNum').innerHTML = stations.length;
        var stationModal = bootstrap.Modal.getInstance(document.getElementById('stationModal'));
        stationModal.hide();
    }
});