{
var waterFall = {
	container: document.getElementById("container"),
	columnNumber: 4,
	columnWidth: 210,
	scrollTop: document.documentElement.scrollTop || document.body.scrollTop,
	detectLeft: 0,
	loadFinish: false,
	pixFromBottom:450,
	data:[],
	
	/**
	 * 初始化瀑布
	 * @param  function getDataFunc 取数据的方法
	 * @return void             [description]
	 */
	init: function(getDataFunc) {
		this.getData = getDataFunc;
		this.getData();
		return;
		/*
		
		//列数变换时才会用到
		if (this.container) {
			this.create().scroll();	//.resize();	
		}
		 */
	},
	// 取数据的方法 外部传入覆盖
	getData: function(){},
	/**
	 * 向结尾追加数据
	 * @param  {pins} data [description]
	 * @return {[type]}      [description]
	 */
	append:function(data){
		for (var i = data.length - 1; i >= 0; i--) {
				eleColumn = waterFall._findShortestColumn();
				waterFall._appendPin(eleColumn, data[i]);
			};
		this.startScrollMonitor();
		return;
	},
	// 是否滚动载入的检测
	_findShortestColumn: function() {
		var start = 0;
		var min = 0;
		var minObj;
		for (start; start < this.columnNumber; start++) {

			var eleColumn = document.getElementById("waterFallColumn_" + start);
			if(start==0||eleColumn.offsetHeight < min){
				min=eleColumn.offsetHeight;
				minObj=eleColumn
			}
		}
		return minObj;
	},
	
	// 滚动载入
	_appendPin: function(column , htmlPin) {

		$(column).append(htmlPin);
		return;

		this.indexImage += 1;
		var html = '', index = this.getIndex(), imgUrl = this.rootImage + "P_" + index + ".jpg";
		
		// 图片尺寸
		var aEle = document.createElement("a");
		aEle.href = "###";
		aEle.className = "pic_a";
		aEle.innerHTML = '<img src="'+ imgUrl +'" /><strong>'+ index +'</strong>';
		column.appendChild(aEle);
		
		if (index >= 160) {
			//alert("图片加载光光了！");
			this.loadFinish = true;
		}
		
		return this;
	},
	// 滚动加载
	startScrollMonitor: function() {
		$(window).bind('scroll' , this._scrollMonitor);
		return;
	},
	removeScrollMonitor:function(){
		$(window).unbind('scroll');
		return;
	},
	_scrollMonitor:function(){
		var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
		var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
		var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;


		console.log("a"+(scrollHeight -( scrollTop + clientHeight)));
		console.log("vv"+waterFall.pixFromBottom);

		if((scrollHeight -( scrollTop + clientHeight)) < waterFall.pixFromBottom){
			console.log('removeScrollMonitor');
			waterFall.removeScrollMonitor();
			
			waterFall.getData();
		}
	},
	
	
	// 页面加载初始创建
	create: function() {
		this.columnNumber = Math.floor(document.body.clientWidth / this.columnWidth);
		
		var start = 0, htmlColumn = '', self = this;
		for (start; start < this.columnNumber; start+=1) {
			htmlColumn = htmlColumn + '<span id="waterFallColumn_'+ start +'" class="column" style="width:'+ this.columnWidth +'px;">'+ 
				function() {
					var html = '', i = 0;
					for (i=0; i<5; i+=1) {
						self.indexImage = start + self.columnNumber * i;
						var index = self.getIndex();
						html = html + '<a href="###" class="pic_a"><img src="'+ self.rootImage + "P_" + index +'.jpg" /><strong>'+ index +'</strong></a>';
					}
					return html;	
				}() +
			'</span> ';	
		}
		htmlColumn += '<span id="waterFallDetect" class="column" style="width:'+ this.columnWidth +'px;"></span>';
		
		this.container.innerHTML = htmlColumn;
		
		this.detectLeft = document.getElementById("waterFallDetect").offsetLeft;
		return this;
	},
	refresh: function() {
		var arrHtml = [], arrTemp = [], htmlAll = '', start = 0, maxLength = 0;
		for (start; start < this.columnNumber; start+=1) {
			var arrColumn = document.getElementById("waterFallColumn_" + start).innerHTML.match(/<a(?:.|\n|\r|\s)*?a>/gi);
			if (arrColumn) {
				maxLength = Math.max(maxLength, arrColumn.length);
				// arrTemp是一个二维数组
				arrTemp.push(arrColumn);
			}
		}
		
		// 需要重新排序
		var lengthStart, arrStart;
		for (lengthStart = 0; lengthStart<maxLength; lengthStart++) {
			for (arrStart = 0; arrStart<this.columnNumber; arrStart++) {
				if (arrTemp[arrStart][lengthStart]) {
					arrHtml.push(arrTemp[arrStart][lengthStart]);	
				}
			}	
		}
		
		
		if (arrHtml && arrHtml.length !== 0) {
			// 新栏个数		
			this.columnNumber = Math.floor(document.body.clientWidth / this.columnWidth);
			
			// 计算每列的行数
			// 向下取整
			var line = Math.floor(arrHtml.length / this.columnNumber);
			
			// 重新组装HTML
			var newStart = 0, htmlColumn = '', self = this;
			for (newStart; newStart < this.columnNumber; newStart+=1) {
				htmlColumn = htmlColumn + '<span id="waterFallColumn_'+ newStart +'" class="column" style="width:'+ this.columnWidth +'px;">'+ 
					function() {
						var html = '', i = 0;
						for (i=0; i<line; i+=1) {
							html += arrHtml[newStart + self.columnNumber * i];
						}
						// 是否补足余数
						html = html + (arrHtml[newStart + self.columnNumber * line] || '');
						
						return html;	
					}() +
				'</span> ';	
			}
			htmlColumn += '<span id="waterFallDetect" class="column" style="width:'+ this.columnWidth +'px;"></span>';
		
			this.container.innerHTML = htmlColumn;
			
			this.detectLeft = document.getElementById("waterFallDetect").offsetLeft;
			
			// 检测
			this.appendDetect();
		}
		return this;
	},
	// 浏览器窗口大小变换
	resize: function() {
		var self = this;
		window.onresize = function() {
			var eleDetect = document.getElementById("waterFallDetect"), detectLeft = eleDetect && eleDetect.offsetLeft;
			if (detectLeft && Math.abs(detectLeft - self.detectLeft) > 50) {
				// 检测标签偏移异常，认为布局要改变
				self.refresh();	
			}
		};
		return this;
	},
	
};
waterFall.init(function(){
	$.getJSON(
	'http://www.meila.com/riaapi/test_pin.php',
	'',
	function(data){
		waterFall.append(data);
		
	}
	);
});

}