<html>
<head> 
<title>123</title>
<script type="text/javascript">
        var savePath = "D:\\capture\\";
        var isStart = false;

        function init() {
            createDir();
        }

        function createDir() {
            captrue.bCreateDir(savePath);
        }

        function startPlay() {
            isStart = captrue.bStartPlay();
        }

        function stopPlay() {
            captrue.bStopPlay();
        }


        function getFileName() {
            var date = new Date();
            var fileName = "" + date.getFullYear();
            var month = date.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            }
            fileName += month;
            var day = date.getDate();
            if (day < 10) {
                day = "0" + day;
            }
            fileName += day;
            var hour = date.getHours();
            if (hour < 10) {
                hour = "0" + hour;
            }
            fileName += hour;

            var minute = date.getMinutes();
            if (minute < 10) {
                minute = "0" + minute;
            }
            fileName += minute;

            var second = date.getSeconds();
            if (second < 10) {
                second = "0" + second;
            }
            fileName += second;
            return fileName;
        }

        function setup() {
            captrue.displayVideoPara();
        }

        function upload() {
			var fileName = getFileName();
            captrue.bSaveJPG(savePath, fileName);
            var port;
            if (location.port != "") {
                port = location.port;
            } else {
                port = 80;
            }
            console.log(captrue.bUpLoadImage(savePath + fileName + ".jpg", "192.168.1.10", port, "/landsystem/phpUploadDemo/upload.php"));
        }

        window.onload = init;
    </script>
</head>  
<body>   
    <div style="text-align:center;" >
	    <object classid="clsid:454C18E2-8B7D-43C6-8C17-B1825B49D7DE" id="captrue" width="600" height="450">
	    </object>
    </div>
    <div style="margin-top: 20px;text-align:center;">
	    <input type="button" value="启动" style="margin-left: 10px;" onclick="startPlay()"/>
	    <input type="button" value="上传" style="margin-left: 10px;" onclick="upload()"/>
	    <input type="button" value="停止" style="margin-left: 10px;" onclick="stopPlay()"/>
		<input type="button" value="参数设置" style="margin-left: 10px;" onclick="setup()"/>
    </div>
</body>
</html>   

