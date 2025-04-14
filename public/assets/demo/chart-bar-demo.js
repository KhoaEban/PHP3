// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart - Doanh Thu Theo Ngày
var ctxBar = document.getElementById("myBarChart");
var myBarChart = new Chart(ctxBar, {
  type: 'bar',
  data: {
    labels: window.chartData.dates, // ["08/04", "09/04", "10/04", "11/04"]
    datasets: [{
      label: "Revenue",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: window.chartData.revenueTotals, // [23750400, 1053000, 0, 1135500]
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'day'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 4
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 25000000, // Đặt max dựa trên giá trị lớn nhất (23750400)
          maxTicksLimit: 5,
          callback: function (value) {
            return (value / 1000000) + 'M'; // Hiển thị đơn vị triệu VNĐ
          }
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});