**什么是ElasticSearch-RTF？**
RTF是Ready To Fly的缩写，在航模里面，表示无需自己组装零件即可直接上手即飞的航空模型，elasticsearch-RTF是针对中文的一个发行版，即使用最新稳定的elasticsearch版本，并且帮你下载测试好对应的插件，如中文分词插件等，还会帮你做好一些默认的配置，目的是让你可以下载下来就可以直接的使用（虽然es已经很简单了，但是很多新手还是需要去花时间去找配置，中间的过程其实很痛苦），当然等你对这些都熟悉了之后，你完全可以自己去diy了，跟linux的众多发行版是一个意思。



**如何使用？**


1.运行环境

	a.JDK7+  
	b.系统可用内存>2G 


2.下载

<pre>git clone git://github.com/medcl/elasticsearch-rtf.git -b master --depth 1</pre>


3.运行

linux:
<pre>cd elasticsearch/bin
./elasticsearch</pre>

windows:
<pre>cd elasticsearch/bin
elasticsearch.bat</pre>