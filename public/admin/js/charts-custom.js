/*global $, document, LINECHARTEXMPLE*/
$(document).ready(function () {
    'use strict';

    var brandPrimary = 'rgba(51, 179, 90, 1)';

    var LINECHARTEXMPLE   = $('#lineChartExample');


    var lineChartExample = new Chart(LINECHARTEXMPLE, {
        type: 'line',
        data: {
        labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11","Tháng 12"],
            datasets: [
                {
                    label: "Doanh thu",
                    fill: true,
                    lineTension: 0.3,
                    backgroundColor: "rgba(51, 179, 90, 0.38)",
                    borderColor: brandPrimary,
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: brandPrimary,
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: brandPrimary,
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: amount,
                    spanGaps: false
                },
            ]
        }
    });
});
