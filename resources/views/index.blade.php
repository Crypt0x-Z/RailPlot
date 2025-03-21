<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg bg-body-tertiary" style="height: 85px;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="assets/logo.png" alt="Logo" width="75px" class="d-inline-block">
                    RailPlot
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-info mx-3" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasWithBothOptions"
                                aria-controls="offcanvasWithBothOptions">Tools</button>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-warning mx-3" onclick="refocusCanvas()">Refocus Grid</button>
                        </li>
                        <li class="nav-item">
                            <button class="btn btn-danger mx-3" onclick="clearGrid()">Clear Grid</button>
                        </li>
                        <table class="mx-5" style="width: 600px;">
                            <tr>
                              <th>Lines</th>
                              <th>Trains</th>
                              <th>Stations</th>
                              <th>PAX on AVG</th>
                              <th>Km of Track</th>
                              <th>Demand</th>
                            </tr>
                            <tr>
                              <td id="linesNum">0</td>
                              <td id="trainsNum">0</td>
                              <td id="stationsNum">0</td>
                              <td id="PoA">0</td>
                              <td id="KmoT">0</td>
                              <td id="demand">Low</td>
                            </tr>
                          </table>

                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-info" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
            aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Tools</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="dropdown my-3">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" style="width: 100%;">
                        Add Line
                    </button>
                    <div class="dropdown-menu p-4" style="width: 100%;">
                        <div class="mb-3">
                            <label for="lineName" class="form-label">Line Name</label>
                            <input type="text" class="form-control" id="lineName">
                        </div>
                        <div class="mb-3">
                            <label for="lineCode" class="form-label">Line Code</label>
                            <input type="text" class="form-control" id="lineCode">
                        </div>
                        <div class="mb-3">
                            <label for="lineColor" class="form-label">Line Color</label>
                            <input type="color" class="form-control" id="lineColor" value="#000000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Line Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lineType" id="lineTypeUnderground"
                                    value="underground">
                                <label class="form-check-label" for="lineTypeUnderground">Underground</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lineType" id="lineTypeGround"
                                    value="ground">
                                <label class="form-check-label" for="lineTypeGround">Ground</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="lineType" id="lineTypeSuspended"
                                    value="suspended">
                                <label class="form-check-label" for="lineTypeSuspended">Suspended</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info" id="saveLine">Save Line</button>
                    </div>
                </div>
                <div class="dropdown my-3">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" style="width: 100%;">
                        Add Train
                    </button>
                    <div class="dropdown-menu p-4" style="width: 100%;">
                        <div class="mb-3">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Train Model</option>
                              </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Add to Line</option>
                              </select>
                        </div>
                        <button type="button" class="btn btn-info" id="saveLine">Save Line</button>
                    </div>
                </div>
            </div>
        </div>

        <canvas id="canvas"></canvas>

        <div id="stationModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Station</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="stationName" class="form-label">Station Name</label>
                            <input type="text" class="form-control" id="stationName">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Station Type (Required)</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="stationType" value="underground" id="undergroundType">
                                <label class="form-check-label" for="undergroundType">Underground</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="stationType" value="ground" id="groundType">
                                <label class="form-check-label" for="groundType">Ground</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="stationType" value="suspended" id="suspendedType">
                                <label class="form-check-label" for="suspendedType">Suspended</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="deleteStation" style="display: none;">Delete Station</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveStation">Save Station</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('js/script.js') }}"></script>
    </body>

    </html>