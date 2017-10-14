    
    <script src="RGraph/libraries/RGraph.common.core.js" ></script>
    <script src="RGraph/libraries/RGraph.common.dynamic.js" ></script>
    <script src="RGraph/libraries/RGraph.common.tooltips.js" ></script>
    <script src="RGraph/libraries/RGraph.common.effects.js" ></script>
    <script src="RGraph/libraries/RGraph.radar.js" ></script>
    <!--[if lt IE 9]><script src="RGraph/excanvas/excanvas.js"></script><![endif]-->


    <canvas id="cvs" width="400" height="400">[No canvas support]</canvas>
    
    <script>
        window.onload = function ()
        {
            var radar2 = new RGraph.Radar('cvs', [4,8,5,2,3],[9,5,6,3,5]);
            radar2.Set('chart.labels', ['Mal', 'Barry', 'Gary','Dave','Paul']);
            radar2.Set('chart.tooltips', ['Mal', 'Barry', 'Gary','Dave','Paul', 'Mal', 'Barry', 'Gary','Dave','Paul']);
            radar2.Set('chart.background.circles.poly', true);
            radar2.Set('chart.background.circles.spacing', 30);
            radar2.Set('chart.colors', ['rgba(255,0,0,0.25)','rgba(255,255,0,0.25)']);
            radar2.Set('chart.axes.color', 'transparent');
            radar2.Set('chart.highlights', true);
            radar2.Set('chart.strokestyle', ['red','black']);
            RGraph.Effects.Radar.Grow(radar2);
        }
    </script>

