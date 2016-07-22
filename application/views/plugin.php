<link rel="stylesheet" type="text/css" href="http://codemirror.net/1/css/csscolors.css">
<link rel="stylesheet" type="text/css" href="http://codemirror.net/1/css/xmlcolors.css">
<link rel="stylesheet" type="text/css" href="http://codemirror.net/1/css/jscolors.css">
<link rel="stylesheet" type="text/css" href="http://v3.bootcss.com/dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="http://v3.bootcss.com/docs-assets/css/docs.css">
<script type="text/javascript" src="http://v3.bootcss.com/dist/js/bootstrap.min.js"></script>
<style type="text/css">
	body{
		padding-top: 0px;
		font-family: "微软雅黑";
		font-size: 14px;
	}
	h1,h2,h3,h4,h5,h6{font-family: "微软雅黑";}
	.example-title{
		padding: 10px 0px 20px;
		color: #999;
	}
	.project_home_quick_register_div{ width: 720px;}
	#getcards_view{
		font-size: 12px;
	}
	#getcards_view .card1 .card1_pt{
		-webkit-box-sizing: content-box;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div id="navigationBar" class="bs-sidebar hidden-print affix" role="complementary">
				<ul class="nav bs-sidenav">
					<li>
						<a href="#messgaebox">弹出框</a>
						<ul class="nav">
							<li class="">
								<a href="#messgaebox-html">html调用</a>
							</li>
							<li class="">
								<a href="#messgaebox-sample">简单js调用</a>
							</li>
							<li class="">
								<a href="#messgaebox-complex">复杂的js调用</a>
							</li>
							<li class="">
								<a href="#messgaebox-complex-more">更复杂的js调用</a>
							</li>
						</ul>
					</li>
					<li class="">
						<a href="#select-areas">选择地区弹框</a>
					</li>
					<li>
						<a href="#select-crowd">适合人群弹框</a>
					</li>
					<li>
						<a href="#select-plug">select插件</a>
					</li>
					<li>
						<a href="#quick-register">快速注册</a>
					</li>
					<li>
						<a href="#send-card-person">个人用户发送名片</a>
					</li>
					<li>
						<a href="#send-card-company">企业用户发送名片</a>
					</li>
					<li>
						<a href="#cascading-dropdown">级联下拉框</a>
					</li>
					<li>
						<a href="#copy-clipboard">复制内容到剪切板</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-9">
			
	<h3 id="messgaebox">弹出框</h3>
	<h4 id="messgaebox-html">1、html调用</h4>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a href="#" class="messageBox" data-content="<p>html调用弹出消息框！</p>" data-btn="ok cancel" data-title="消息框" data-type="click" data-width="300" data-callback="function(){alert('点击了确定');}">点击查看效果</a>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"messageBox" </span><span class="xml-attname">data-content</span><span class="xml-punctuation">=</span><span class="xml-attribute">"&lt;p&gt;html调用弹出消息框！&lt;/p&gt;" </span><span class="xml-attname">data-btn</span><span class="xml-punctuation">=</span><span class="xml-attribute">"ok cancel" </span><span class="xml-attname">data-title</span><span class="xml-punctuation">=</span><span class="xml-attribute">"消息框" </span><span class="xml-attname">data-type</span><span class="xml-punctuation">=</span><span class="xml-attribute">"click" </span><span class="xml-attname">data-width</span><span class="xml-punctuation">=</span><span class="xml-attribute">"300" </span><span class="xml-attname">data-callback</span><span class="xml-punctuation">=</span><span class="xml-attribute">"function(){alert('点击了确定');}"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">点击查看效果</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>class="messageBox"</td>
						<td>必选</td>
						<td>用于触发弹出框；</td>
					</tr>
					<tr>
						<td>data-content</td>
						<td>必选</td>
						<td>需要显示的内容；</td>
					</tr>
					<tr>
						<td>data-btn</td>
						<td>可选</td>
						<td>弹框的按钮，ok为确定，cancel为取消；</td>
					</tr>
					<tr>
						<td>data-title</td>
						<td>可选</td>
						<td>弹框标题；</td>
					</tr>
					<tr>
						<td>data-type</td>
						<td>可选</td>
						<td>弹框的触发方式，click为点击触发，为空则通过js触发；</td>
					</tr>
					<tr>
						<td>data-width</td>
						<td>可选</td>
						<td>弹框宽度；</td>
					</tr>
					<tr>
						<td>data-callback</td>
						<td>可选</td>
						<td>点击确定时的回调函数</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h4 id="messgaebox-sample">2、简单js调用</h4>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a href="javascript:void(0)" onclick="window.MessageBox('弹出框简单调用');">点击弹出消息框</a>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">onclick</span><span class="xml-punctuation">=</span><span class="xml-attribute">"window.MessageBox('弹出框简单调用');"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">点击弹出消息框</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>window.MessageBox(string msg);</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>msg(string)</td>
						<td>必选</td>
						<td>需要显示的消息</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h4 id="messgaebox-complex">3、复杂的js调用</h4>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a href="javascript:void(0)" onclick="showMessageBoxTemp()">点击弹出消息框</a>
			<script type="text/javascript">
				function showMessageBoxTemp(){
					//复杂的js调用主体
					window.MessageBox({
						title:"标题",
						content:"<p>内容</p>",
						btn:"ok cancel",
						width:400,
						callback:function(){
							alert("调用了回调函数");
						},
						onclose:function(){
							alert("关闭时触发的函数");
							return true;
						},
						target:"new"
					});
					//复杂的js调用主体 END

				}
			</script>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">onclick</span><span class="xml-punctuation">=</span><span class="xml-attribute">"showMessageBox()"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">点击弹出消息框</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">script </span><span class="xml-attname">type</span><span class="xml-punctuation">=</span><span class="xml-attribute">"text/javascript"</span><span class="xml-punctuation">&gt;</span><br><span class="js-keyword">function </span><span class="js-variable">showMessageBox</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-comment">//复杂的js调用主体</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-variable">window</span><span class="js-punctuation">.</span><span class="js-property">MessageBox</span><span class="js-punctuation">(</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">title</span><span class="js-punctuation">:</span><span class="js-string">"标题"</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">content</span><span class="js-punctuation">:</span><span class="js-string">"&lt;p&gt;内容&lt;/p&gt;"</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">btn</span><span class="js-punctuation">:</span><span class="js-string">"ok cancel"</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">width</span><span class="js-punctuation">:</span><span class="js-atom">400</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">callback</span><span class="js-punctuation">:</span><span class="js-keyword">function</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">alert</span><span class="js-punctuation">(</span><span class="js-string">"调用了回调函数"</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">onclose</span><span class="js-punctuation">:</span><span class="js-keyword">function</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">alert</span><span class="js-punctuation">(</span><span class="js-string">"关闭时触发的函数"</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">return </span><span class="js-atom">true</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-property">target</span><span class="js-punctuation">:</span><span class="js-string">"new"</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-punctuation">}</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-comment">//复杂的js调用主体 END</span><br><br><span class="js-punctuation">}</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">script</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>window.MessageBox(Object option);</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>title</td>
						<td>可选</td>
						<td>弹框的标题</td>
					</tr>
					<tr>
						<td>content</td>
						<td>必选</td>
						<td>需要显示的内容</td>
					</tr>
					<tr>
						<td>btn</td>
						<td>可选</td>
						<td>弹框的按钮</td>
					</tr>
					<tr>
						<td>width</td>
						<td>可选</td>
						<td>弹框的宽度</td>
					</tr>
					<tr>
						<td>callback</td>
						<td>可选</td>
						<td>回调函数（点击确定是会触发）</td>
					</tr>
					<tr>
						<td>onclose</td>
						<td>可选</td>
						<td>关闭弹框是触发的函数</td>
					</tr>
					<tr>
						<td>target</td>
						<td>可选</td>
						<td>打开一个新的弹框（具体效果请同时调用弹框函数两次）</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h4 id="messgaebox-complex-more">4、更复杂的js调用</h4>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a href="javascript:void(0)" id="messgaeboxComplexBtn" onclick="showMessageBox(this)">点击弹出消息框</a>
			<script type="text/javascript">
				var count,handler;
				new messageBox($("#messgaeboxComplexBtn")[0], {
					title:"更复杂的js调用",
					content:"<p>弹框将于4秒后消失</p>",
					btn:"ok cancel",
					width:400,
					onclose:function(){
						count=5;
						clearInterval(handler);
						return true;
					}
				});

				function showMessageBox(obj){
					count = 5;
					obj.show();
					hideMessageBox(obj);
					handler = setInterval(function(){hideMessageBox(obj);}, 1000);
				}

				function hideMessageBox(obj){
					$(obj.messageBox.box).find("dd:eq(0) p").html("弹框将于"+(count--)+"秒后消失");
					if(count==-1){
						obj.hide();
						clearInterval(handler);
						return false;
					}
				}
			</script>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"messgaeboxComplexBtn" </span><span class="xml-attname">onclick</span><span class="xml-punctuation">=</span><span class="xml-attribute">"showMessageBox(this)"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">点击弹出消息框</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">script </span><span class="xml-attname">type</span><span class="xml-punctuation">=</span><span class="xml-attribute">"text/javascript"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">var </span><span class="js-variable">count</span><span class="js-punctuation">,</span><span class="js-variable">handler</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">new </span><span class="js-variable">messageBox</span><span class="js-punctuation">(</span><span class="js-variable">$</span><span class="js-punctuation">(</span><span class="js-string">"#messgaeboxComplexBtn"</span><span class="js-punctuation">)</span><span class="js-punctuation">[</span><span class="js-atom">0</span><span class="js-punctuation">]</span><span class="js-punctuation">, </span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-property">title</span><span class="js-punctuation">:</span><span class="js-string">"更复杂的js调用"</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-property">content</span><span class="js-punctuation">:</span><span class="js-string">"&lt;p&gt;弹框将于4秒后消失&lt;/p&gt;"</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-property">btn</span><span class="js-punctuation">:</span><span class="js-string">"ok cancel"</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-property">width</span><span class="js-punctuation">:</span><span class="js-atom">400</span><span class="js-punctuation">,</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-property">onclose</span><span class="js-punctuation">:</span><span class="js-keyword">function</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">count</span><span class="js-operator">=</span><span class="js-atom">5</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">clearInterval</span><span class="js-punctuation">(</span><span class="js-variable">handler</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">return </span><span class="js-atom">true</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">function </span><span class="js-variable">showMessageBox</span><span class="js-punctuation">(</span><span class="js-variabledef">obj</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">count </span><span class="js-operator">= </span><span class="js-atom">5</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-localvariable">obj</span><span class="js-punctuation">.</span><span class="js-property">show</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">hideMessageBox</span><span class="js-punctuation">(</span><span class="js-localvariable">obj</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">handler </span><span class="js-operator">= </span><span class="js-variable">setInterval</span><span class="js-punctuation">(</span><span class="js-keyword">function</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><span class="js-variable">hideMessageBox</span><span class="js-punctuation">(</span><span class="js-localvariable">obj</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><span class="js-punctuation">}</span><span class="js-punctuation">, </span><span class="js-atom">1000</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><br><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">function </span><span class="js-variable">hideMessageBox</span><span class="js-punctuation">(</span><span class="js-variabledef">obj</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">$</span><span class="js-punctuation">(</span><span class="js-localvariable">obj</span><span class="js-punctuation">.</span><span class="js-property">messageBox</span><span class="js-punctuation">.</span><span class="js-property">box</span><span class="js-punctuation">)</span><span class="js-punctuation">.</span><span class="js-property">find</span><span class="js-punctuation">(</span><span class="js-string">"dd:eq(0) p"</span><span class="js-punctuation">)</span><span class="js-punctuation">.</span><span class="js-property">html</span><span class="js-punctuation">(</span><span class="js-string">"弹框将于"</span><span class="js-operator">+</span><span class="js-punctuation">(</span><span class="js-variable">count</span><span class="js-operator">--</span><span class="js-punctuation">)</span><span class="js-operator">+</span><span class="js-string">"秒后消失"</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">if</span><span class="js-punctuation">(</span><span class="js-variable">count</span><span class="js-operator">==-</span><span class="js-atom">1</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-localvariable">obj</span><span class="js-punctuation">.</span><span class="js-property">hide</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-variable">clearInterval</span><span class="js-punctuation">(</span><span class="js-variable">handler</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-keyword">return </span><span class="js-atom">false</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span class="js-punctuation">}</span><br><span class="whitespace">&nbsp; &nbsp; &nbsp;&nbsp;</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">script</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>new messageBox(Object target, Object option);</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>title</td>
						<td>可选</td>
						<td>弹框的标题</td>
					</tr>
					<tr>
						<td>content</td>
						<td>必选</td>
						<td>需要显示的内容</td>
					</tr>
					<tr>
						<td>btn</td>
						<td>可选</td>
						<td>弹框的按钮</td>
					</tr>
					<tr>
						<td>width</td>
						<td>可选</td>
						<td>弹框的宽度</td>
					</tr>
					<tr>
						<td>callback</td>
						<td>可选</td>
						<td>回调函数（点击确定是会触发）</td>
					</tr>
					<tr>
						<td>onclose</td>
						<td>可选</td>
						<td>关闭弹框是触发的函数</td>
					</tr>
					<tr>
						<td>target</td>
						<td>可选</td>
						<td>打开一个新的弹框（具体效果请同时调用弹框函数两次）</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h3 id="select-areas">选择地区弹框</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<div class="list_li" id="diqu"></div>
			<div href="#" id="addressClickEffect">
				<a href="#" id="addImg">选择地区</a>
				<a href="#" id="addImg2">修改地区</a>
			</div>
			<script type="text/javascript">
    			var select_area_list = '<li><span>广东</span><input type="hidden" name="true" class="1" /></li><li><span>北京</span><input type="hidden" name="true" class="2" /></li><li><span>天津</span><input type="hidden" name="true" class="3" /></li>';
			</script>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">div </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"list_li" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"diqu"</span><span class="xml-punctuation">&gt;</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">div</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">div </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"addressClickEffect"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"addImg"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">选择地区</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"addImg2"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">修改地区</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">div</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">script </span><span class="xml-attname">type</span><span class="xml-punctuation">=</span><span class="xml-attribute">"text/javascript"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-keyword">var </span><span class="js-variable">select_area_list </span><span class="js-operator">= </span><span class="js-string">'&lt;li&gt;&lt;span&gt;广东&lt;/span&gt;&lt;input type="hidden" name="true" class="1" /&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;北京&lt;/span&gt;&lt;input type="hidden" name="true" class="2" /&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;天津&lt;/span&gt;&lt;input type="hidden" name="true" class="3" /&gt;&lt;/li&gt;'</span><span class="js-punctuation">;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">script</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				&lt;div class="list_li" id="diqu">&lt;/div><br/>必须，用于存放选择结果；<br/><br/>
				&lt;div href="#" id="addressClickEffect"><br/>ID为“addressClickEffect”作为触发器（即点击这个按钮将显示弹框）<br/><br/>
				&lt;a href="#" id="addImg">选择地区&lt;/a><br/>
				&lt;a href="#" id="addImg2">修改地区&lt;/a><br/>“addImg”，是显示初始时的按钮，“addImg2”，是选择过后的按钮<br/><br/>
				&lt;script type="text/javascript"><br/>
    			var select_area_list = '&lt;li>&lt;span>广东&lt;/span>&lt;input type="hidden" name="true" class="1" />&lt;/li>&lt;li>&lt;span>北京&lt;/span>&lt;input type="hidden" name="true" class="2" />&lt;/li>&lt;li>&lt;span>天津&lt;/span>&lt;input type="hidden" name="true" class="3" />&lt;/li>';<br/>
				&lt;/script><br/>
				select_area_list：这个变量用于存放初始时的省份数据
			</p>
		</div>
	</div>
	<h3 id="select-crowd">选择适合人群弹出框</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<div>
				<div class="list_li zhaos_result">
					<ul></ul>
				</div>
				<a href="#" id="crowd_btn">适合人群</a>
				<a href="#" id="crowd_btn2">重新添加</a>
			</div>
			<script type="text/javascript">
			$(document).ready(function(){
				var  str_data ={"1":"首次创业","2":"二次创业","3":"大学生创业","4":"女性投资","6":"农民致富","7":"80后创业","18":"白领","19":"蓝领","25":"小额成本","26":"学生","27":"企业家","28":"赋闲在家","29":"公务员","30":"合资创业","31":"公务员"};
				new zhaos_box("#crowd_btn", str_data);
			});
			</script>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">div</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">div </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"list_li zhaos_result"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">ul</span><span class="xml-punctuation">&gt;</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">ul</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">div</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"crowd_btn"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">适合人群</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"crowd_btn2"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">重新添加</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">div</span><span class="xml-punctuation">&gt;</span><br><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">script </span><span class="xml-attname">type</span><span class="xml-punctuation">=</span><span class="xml-attribute">"text/javascript"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-variable">$</span><span class="js-punctuation">(</span><span class="js-variable">document</span><span class="js-punctuation">)</span><span class="js-punctuation">.</span><span class="js-property">ready</span><span class="js-punctuation">(</span><span class="js-keyword">function</span><span class="js-punctuation">(</span><span class="js-punctuation">)</span><span class="js-punctuation">{</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-keyword">var&nbsp;&nbsp;</span><span class="js-variabledef">str_data </span><span class="js-operator">=</span><span class="js-punctuation">{</span><span class="js-string">"1"</span><span class="js-punctuation">:</span><span class="js-string">"首次创业"</span><span class="js-punctuation">,</span><span class="js-string">"2"</span><span class="js-punctuation">:</span><span class="js-string">"二次创业"</span><span class="js-punctuation">,</span><span class="js-string">"3"</span><span class="js-punctuation">:</span><span class="js-string">"大学生创业"</span><span class="js-punctuation">,</span><span class="js-string">"4"</span><span class="js-punctuation">:</span><span class="js-string">"女性投资"</span><span class="js-punctuation">,</span><span class="js-string">"6"</span><span class="js-punctuation">:</span><span class="js-string">"农民致富"</span><span class="js-punctuation">,</span><span class="js-string">"7"</span><span class="js-punctuation">:</span><span class="js-string">"80后创业"</span><span class="js-punctuation">,</span><span class="js-string">"18"</span><span class="js-punctuation">:</span><span class="js-string">"白领"</span><span class="js-punctuation">,</span><span class="js-string">"19"</span><span class="js-punctuation">:</span><span class="js-string">"蓝领"</span><span class="js-punctuation">,</span><span class="js-string">"25"</span><span class="js-punctuation">:</span><span class="js-string">"小额成本"</span><span class="js-punctuation">,</span><span class="js-string">"26"</span><span class="js-punctuation">:</span><span class="js-string">"学生"</span><span class="js-punctuation">,</span><span class="js-string">"27"</span><span class="js-punctuation">:</span><span class="js-string">"企业家"</span><span class="js-punctuation">,</span><span class="js-string">"28"</span><span class="js-punctuation">:</span><span class="js-string">"赋闲在家"</span><span class="js-punctuation">,</span><span class="js-string">"29"</span><span class="js-punctuation">:</span><span class="js-string">"公务员"</span><span class="js-punctuation">,</span><span class="js-string">"30"</span><span class="js-punctuation">:</span><span class="js-string">"合资创业"</span><span class="js-punctuation">,</span><span class="js-string">"31"</span><span class="js-punctuation">:</span><span class="js-string">"公务员"</span><span class="js-punctuation">}</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp; &nbsp;&nbsp;</span><span class="js-keyword">new </span><span class="js-variable">zhaos_box</span><span class="js-punctuation">(</span><span class="js-string">"#crowd_btn"</span><span class="js-punctuation">, </span><span class="js-localvariable">str_data</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="js-punctuation">}</span><span class="js-punctuation">)</span><span class="js-punctuation">;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">script</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				&lt;div class="list_li zhaos_result">&lt;br/>
    				&lt;ul>&lt;/ul>&lt;br/>
  				&lt;/div><br/>必须，用于存放选择结果；<br/><br/>
				
				&lt;a href="#" id="crowd_btn">选择地区&lt;/a><br/>
				&lt;a href="#" id="crowd_btn2">修改地区&lt;/a><br/>“crowd_btn”，是显示初始时的按钮，“crowd_btn2”，是选择过后的按钮<br/><br/>
				&lt;script type="text/javascript"><br/>
  					$(document).ready(function(){<br/>
    					var  str_data ={"1":"首次创业","2":"二次创业","3":"大学生创业","4":"女性投资","6":"农民致富","7":"80后创业","18":"白领","19":"蓝领","25":"小额成本","26":"学生","27":"企业家","28":"赋闲在家","29":"公务员","30":"合资创业","31":"公务员"};<br/>
    					new zhaos_box("#crowd_btn", str_data);<br/>
  					});<br/>
				&lt;/script><br/>
				str_data：这个变量用于存放初始时的适合人群<br/>
				new zhaos_box(objject obj, object data);

			</p>
		</div>
	</div>
	<h3 id="select-plug">select插件</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<div style="position:relative;">
				<a href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".per_area_id" second-result=".per_city_id" box-title="省级" select-all="clear" callback="alert(area_id);">点击查看效果</a>
			</div>
			<input type="hidden" class="per_area_id"/>
			<input type="hidden" class="per_city_id">
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">div </span><span class="xml-attname">style</span><span class="xml-punctuation">=</span><span class="xml-attribute">"position:relative;"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"#" </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"select_area_toggle" </span><span class="xml-attname">data-url</span><span class="xml-punctuation">=</span><span class="xml-attribute">"/ajaxcheck/getArea" </span><span class="xml-attname">first-result</span><span class="xml-punctuation">=</span><span class="xml-attribute">".per_area_id" </span><span class="xml-attname">second-result</span><span class="xml-punctuation">=</span><span class="xml-attribute">".per_area_id" </span><span class="xml-attname">box-title</span><span class="xml-punctuation">=</span><span class="xml-attribute">"省级" </span><span class="xml-attname">select-all</span><span class="xml-punctuation">=</span><span class="xml-attribute">"clear" </span><span class="xml-attname">callback</span><span class="xml-punctuation">=</span><span class="xml-attribute">"alert(area_id);"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">点击查看效果</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">div</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				用于触发的标签父级标签position必须为relative<br/>
			</p>
			<h6>*将first-result和second-result设置为同一表单可仅获取选择的二级数据</h6>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>class="select_area_toggle"</td>
						<td>必选</td>
						<td>用于触发弹框</td>
					</tr>
					<tr>
						<td>data-url</td>
						<td>必选</td>
						<td>指定AJAX加载数据的URL</td>
					</tr>
					<tr>
						<td>first-result</td>
						<td>必选</td>
						<td>存放一级选项结果的表单</td>
					</tr>
					<tr>
						<td>second-result</td>
						<td>必选</td>
						<td>存放一级选项结果的表单</td>
					</tr>
					<tr>
						<td>box-title</td>
						<td>必选</td>
						<td>选择框的标题</td>
					</tr>
					<tr>
						<td>select-all</td>
						<td>可选</td>
						<td>显示不限，默认为false，可选参数有：true（只在二级显示）、clear（在一级二级都显示）、false(不显示)</td>
					</tr>
					<tr>
						<td>callback</td>
						<td>可选</td>
						<td>选择结束的回调函数，选择的结果保存在area_id</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h3 id="quick-register">快速注册</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a href="javascript:void(0)" onclick="quickRegister('19840', '快速注册')" >快速注册</a>（请退出当前账户再查看效果）
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">onclick</span><span class="xml-punctuation">=</span><span class="xml-attribute">"quickRegister('19840', '快速注册')" </span><span class="xml-punctuation">&gt;</span><span class="xml-text">快速注册</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				quickRegister(string projectId, string title)
			</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>projectId</td>
						<td>必选</td>
						<td>注册完成发送名片的企业</td>
					</tr>
					<tr>
						<td>title</td>
						<td>必选</td>
						<td>快速注册框的标题</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h3 id="send-card-person">个人用户发送名片</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a rel="nofollow" class="consult" id="zx_19840_83746_1" href="javascript:void(0)" title="我要咨询">我要咨询</a>
			<a rel="nofollow" class="consult" id="syzl_19840_83746_2" href="javascript:void(0)" title="索要资料">索要资料</a>
			<a rel="nofollow" class="consult" id="fsyx_19840_83746_3" href="javascript:void(0)" title="发送意向">发送意向</a>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">rel</span><span class="xml-punctuation">=</span><span class="xml-attribute">"nofollow" </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"consult" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"zx_19840_83746_1" </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">title</span><span class="xml-punctuation">=</span><span class="xml-attribute">"我要咨询"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">我要咨询</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">rel</span><span class="xml-punctuation">=</span><span class="xml-attribute">"nofollow" </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"consult" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"syzl_19840_83746_2" </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">title</span><span class="xml-punctuation">=</span><span class="xml-attribute">"索要资料"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">索要资料</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">rel</span><span class="xml-punctuation">=</span><span class="xml-attribute">"nofollow" </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"consult" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"fsyx_19840_83746_3" </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">title</span><span class="xml-punctuation">=</span><span class="xml-attribute">"发送意向"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">发送意向</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				&lt;a rel="nofollow" class="consult" id="[zx|syzl|fsyx]_[project id]_[company user id]_[type]" href="javascript:void(0)" title="我要咨询">我要咨询&lt;/a>
			</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>class="consult"</td>
						<td>必选</td>
						<td>用于触发弹框</td>
					</tr>
					<tr>
						<td>[zx|syzl|fsyx]</td>
						<td>必选</td>
						<td>zx（咨询）、syzl（索要资料）、fsyx（发送意向）根据调用的位置，选择一项</td>
					</tr>
					<tr>
						<td>[project id]</td>
						<td>必选</td>
						<td>项目ID</td>
					</tr>
					<tr>
						<td>[company user id]</td>
						<td>必选</td>
						<td>企业用户ID</td>
					</tr>
					<tr>
						<td>[type]</td>
						<td>必选</td>
						<td>类别：1（咨询）、2（索要资料）、3（发送意向）</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h3 id="send-card-company">企业用户发送名片</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<a href="javascript:void(0)" class="send_letter" id="letter_83895_1_711321_1" perinfo="http://pic.yijuhua-alpha.net/|gon|天津|教育培训|5万以下|1|2|150" rel="nofollow">发信</a>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"send_letter" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"letter_83895_1_711321_1" </span><span class="xml-attname">perinfo</span><span class="xml-punctuation">=</span><span class="xml-attribute">"http://pic.yijuhua-alpha.net/|gon|天津|教育培训|5万以下|1|2|150" </span><span class="xml-attname">rel</span><span class="xml-punctuation">=</span><span class="xml-attribute">"nofollow"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">发信</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>class="send_letter"</td>
						<td>必选</td>
						<td>用于触发弹框</td>
					</tr>
					<tr>
						<td>id</td>
						<td>必选</td>
						<td>letter_[个人用户ID]_[发送名片状态]_[名片ID]_1</td>
					</tr>
					<tr>
						<td>perinfo</td>
						<td>必选</td>
						<td>[头像url]|[姓名]|[所在地]|[意向投资行业]|[意向金额]|[性别]|[获取头像各种情况判断]|[活跃度]</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h3 id="cascading-dropdown">级联下拉框</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<form>
				<select class="cascadingDropList">
					<option>请选择地区</option>
				</select>
				<select>
					<option>请选择地区</option>
				</select>
			</form>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">select </span><span class="xml-attname">class</span><span class="xml-punctuation">=</span><span class="xml-attribute">"cascadingDropList"</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">option</span><span class="xml-punctuation">&gt;</span><span class="xml-text">请选择地区</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">option</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">select</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">select</span><span class="xml-punctuation">&gt;</span><br><span class="whitespace">&nbsp;&nbsp;</span><span class="xml-punctuation">&lt;</span><span class="xml-tagname">option</span><span class="xml-punctuation">&gt;</span><span class="xml-text">请选择地区</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">option</span><span class="xml-punctuation">&gt;</span><br><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">select</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				请保证class加在第一级select上，二级select与一级相邻
			</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>class="cascadingDropList"</td>
						<td>必选</td>
						<td>用于触发级联下拉</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<h3 id="copy-clipboard">复制内容到剪切板</h3>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="example-title">示例</div>
			<input type="text" id="copyText" value="要复制的内容" />
			<a href="javascript:void(0)" onclick="copyToClipboard($('#copyText').val(), $('#copyText')[0])">点击复制</a>
		</div>
		<div class="panel-body">
			<span class="xml-punctuation">&lt;</span><span class="xml-tagname">input </span><span class="xml-attname">type</span><span class="xml-punctuation">=</span><span class="xml-attribute">"text" </span><span class="xml-attname">id</span><span class="xml-punctuation">=</span><span class="xml-attribute">"copyText" </span><span class="xml-attname">value</span><span class="xml-punctuation">=</span><span class="xml-attribute">"要复制的内容" </span><span class="xml-punctuation">/&gt;</span><br><span class="xml-punctuation">&lt;</span><span class="xml-tagname">a </span><span class="xml-attname">href</span><span class="xml-punctuation">=</span><span class="xml-attribute">"javascript:void(0)" </span><span class="xml-attname">onclick</span><span class="xml-punctuation">=</span><span class="xml-attribute">"copyToClipboard($('#copyText').val(), $('#copyText')[0])"</span><span class="xml-punctuation">&gt;</span><span class="xml-text">点击复制</span><span class="xml-punctuation">&lt;/</span><span class="xml-tagname">a</span><span class="xml-punctuation">&gt;</span><br>
		</div>
		<div class="panel-footer">
			<p>
				copyToClipboard(string text, object obj)
			</p>
			<table class="table">
				<tbody>
					<tr>
						<th>参数名</th>
						<th>是否必须</th>
						<th>说明</th>
					</tr>
					<tr>
						<td>text</td>
						<td>必选</td>
						<td>需要放入剪切板的内容</td>
					</tr>
					<tr>
						<td>obj</td>
						<td>必选</td>
						<td>在不支持自动复制时，会将text放入obj并选中内容</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('body').scrollspy({ target: '#navigationBar' })
</script>