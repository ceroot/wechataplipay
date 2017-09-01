/**
 * Created by liushizhao on 2017/7/9.
 */
(function ($) {
    /**
     * 初始化ajax
     * @param url
     * @param data
     * @param options
     */
    function ajaxFun(url,data,options) {
        this.url=url;
        this.datas=data;
        //合并参数
        this.opts=$.extend({},ajaxFun.DEFAULTS,options);
        if(this.opts.type==="get"||this.opts.type===0){
            this._ajaxget();
        }
        if(this.opts.type==="post"||this.opts.type===1){
            this._ajaxpost();
        }
        if(this.opts.type=="uploads"||this.opts.type===2){
            this._ajaxUploads();
        }
    }

    /**
     * ajax 默认参数 对象
     * @type {{method: string, dataType: string, cache: boolean, contentType: boolean, processData: boolean, beforeFun: null, successFun: null, errorFun: null, complateFun: null}}
     */
    ajaxFun.DEFAULTS={
        type:"post",
        dataType:'json',
        cache: false, //上传文件是默认为false
        contentType: false, //string 类型   上传文件时为false  默认值: "application/x-www-form-urlencoded"。发送信息至服务器时内容编码类型。
        processData: false,//Boolean 上传文件时为false 默认值: true。默认情况下，通过data选项传递进来的数据，如果是一个对象(技术上讲只要不是字符串)，都会处理转化成一个查询字符串，以配合默认内容类型 "application/x-www-form-urlencoded"。如果要发送 DOM 树信息或其它不希望转换的信息，请设置为 false。
        b:null, //执行ajax之前执行的方法
        ok:null,//ajax成功返回的方法
        no:null,//ajax错误返回方法
        over:null,//ajax执行完，执行的方法无论成功失败都会执行
        fileType:"image"//验证上传文件格式 是不是图片  默认为图片格式 其他格式暂不做验证
    };

    /**
     * ajax get 提交
     * @private
     */
    ajaxFun.prototype._ajaxget=function () {
        var opts=this.opts,
            url=this.url,
            datas=this.datas;

        $.ajax({
            type: 'get',
            url: url,
            data:datas,
            dataType:opts.dataType,
            beforeSend:function () {
                opts.b &&  opts.b();
            },
            success:function (callBack) {
                opts.ok &&opts.ok(callBack)
            },
            error:function () {
                opts.no && opts.no();
            },
            complete:function () {
                opts.over && opts.over();
            }
        })
    };
    /**
     * ajax post提交
     * @private
     */
    ajaxFun.prototype._ajaxpost=function () {
        var opts=this.opts,
            url=this.url,
            datas=this.datas;
        $.ajax({
            type:'post',
            url: url,
            data:datas,
            dataType:opts.dataType,
            beforeSend:function () {
                opts.b &&  opts.b();
            },
            success:function (callBack) {
                opts.ok &&opts.ok(callBack)
            },
            error:function () {
                opts.no && opts.no();
            },
            complete:function () {
                opts.over && opts.over();
            }
        })
    };
    ajaxFun.prototype._ajaxUploads=function (e) {
        var opts=this.opts,
            url=this.url;
        var data=new FormData();
        for(var i=0;i<e.target.files.length;i++)
        {
            var file = e.target.files.item(i);
            //判断类型
            if(opts.fileType=="image"){
                if(!/image\/\w+/.test(file.type))
                {
                    continue;   //不是图片 就跳出这一次循环
                }
                else{

                }
            }else{
                data.append("file[]",file);
            }

        }

        $.ajax({
            data: data,
            type: "POST",
            url: url,
            cache: opts.cache,
            contentType: opts.contentType,
            processData: opts.processData,
            beforeSend:function () {
                opts.b &&  opts.b();
            },
            success:function (callBack) {
                opts.ok &&opts.ok(callBack)
            },
            error:function () {
                opts.no && opts.no();
            },
            complete:function () {
                opts.over && opts.over();
            }
        });


    };
    /**
     * 生成ajax插件
     */
    $.extend({
        ajaxFun:function (url,data,options) {
            //实例化ajax
            new ajaxFun(url,data,options);
        }
    })
})(jQuery);