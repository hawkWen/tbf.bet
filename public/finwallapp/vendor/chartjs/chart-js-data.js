/* Dashboard chart combo line and bar */
/* sales area chart */
var areachart = document.getElementById('mixedchartjs').getContext('2d');
var gradient1 = areachart.createLinearGradient(0, 0, 0, 400);
gradient1.addColorStop(0, 'rgb(0, 186, 255)');
gradient1.addColorStop(0.5, 'rgba(0, 186, 255, 0)');
var gradient2 = areachart.createLinearGradient(0, 0, 0, 400);
gradient2.addColorStop(0, 'rgb(255, 82, 122)');
gradient2.addColorStop(0.5, 'rgba(255, 82, 122, 0)');

area();

function area() {

    var configareachart = {
        type: 'bar',
        data: {
            labels: ['0', 'Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7'],
            datasets: [{
                label: 'My First dataset',
                borderWidth: '1',
                borderColor: '#00baff',
                backgroundColor: gradient1,
                data: [
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor()
                    ],
                }, {
                label: 'My First dataset',
                borderWidth: '1',
                borderColor: '#ff3f73',
                backgroundColor: gradient2,
                data: [
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor()
                    ],
                }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                point: {
                    radius: '1',
                }
            },
            title: {
                display: false,
                text: 'Chart.js Line Chart - Stacked Area'
            },
            tooltips: {
                mode: 'index',
            },
            hover: {
                mode: 'index'
            },
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    ticks: {
                        display: false,
                        fontColor: "#90b5ff",
                    },
                    display: false,
                    stacked: false,
                    scaleLabel: {
                        display: false,
                        labelString: 'Month'
                    }
                    }],
                yAxes: [{
                    ticks: {
                        display: false,
                        fontColor: "#90b5ff",
                    },
                    display: false,
                    stacked: false,
                    scaleLabel: {
                        display: false,
                        labelString: 'Value'
                    }
                    }]
            }
        }
    };

    window.salesareachart = new Chart(areachart, configareachart);

    setInterval(function () {
        configareachart.data.datasets.forEach(function (dataset) {
            dataset.data = dataset.data.map(function () {
                return randomScalingFactor();
            });

        });
        window.salesareachart.update();
    }, 1100);

}



doghnut();

function doghnut() {


    var config2 = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
						randomScalingFactor(),
						randomScalingFactor(),
					],
                backgroundColor: [
						'#f4f4f4',
						'#67e8b1',
					],
                label: false
				}],
            labels: [
					'Red',
					'Orange',
				]
        },
        options: {
            responsive: true,
            legend: false,
            label: false,
            title: {
                display: false,
                text: 'Chart.js Doughnut Chart'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };


    var ctx = document.getElementById('doghnutchart').getContext('2d');
    var ctx5 = document.getElementById('doghnutchart5').getContext('2d');

    
    window.myDoughnut = new Chart(ctx, config2);
    window.myDoughnut5 = new Chart(ctx5, config2);

    setInterval(function () {
        config2.data.datasets.forEach(function (dataset) {
            dataset.data = dataset.data.map(function () {
                return randomScalingFactor();
            });

        });
        window.myDoughnut.update();
    }, 1100);


}
doghnut3();

function doghnut3() {


    var config3 = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
						randomScalingFactor(),
						randomScalingFactor(),
					],
                backgroundColor: [
						'#f4f4f4',
						'#ff6060',
					],
                label: false
				}],
            labels: [
					'Red',
					'Orange',
				]
        },
        options: {
            responsive: true,
            legend: false,
            label: false,
            title: {
                display: false,
                text: 'Chart.js Doughnut Chart'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };


    var ctx = document.getElementById('doghnutchart3').getContext('2d');
    var ctx4 = document.getElementById('doghnutchart4').getContext('2d');

    window.myDoughnut2 = new Chart(ctx, config3);
    window.myDoughnut4 = new Chart(ctx4, config3);

    setInterval(function () {
        config3.data.datasets.forEach(function (dataset) {
            dataset.data = dataset.data.map(function () {
                return randomScalingFactor();
            });

        });
        window.myDoughnut2.update();
    }, 1100);


}
