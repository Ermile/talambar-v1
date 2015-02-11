!function(root){"use strict";var defaults={ajax:{type:"post",contentType:!1,processData:!1,dataType:"text",mimeType:"text/plain",url:"http://localhost:8000"},type:"BinaryString",originalFile:null,file:null,result:null,range:[0,0],parts:200,rest:20,restDuration:500,beforeStart:_.noop,afterCheck:_.noop,progress:_.noop,done:_.noop},FileManager=function(options){var ajax=_.extend({},defaults.ajax,options.ajax);_.extend(this,defaults,options),this.ajax=ajax,this.originalFile=this.file,this.size=this.originalFile.size,this.fileType=this.originalFile.type,this.range=[0,this.size],this.sessionID=0,this.fileID=0};FileManager.prototype={_load:function(){var deferred=new jQuery.Deferred;this.fileReader=new FileReader;var self=this;return this.fileReader.onload=function(e){self.result=e.target.result,deferred.resolve(self)},this.fileReader.onerror=this.fileReader.onabort=function(e){deferred.reject(e)},this.fileReader["readAs"+this.type](this.file),deferred.promise()},load:function(){return this._load.apply(this,arguments)},_slice:function(start,end){return this.file=this.originalFile.slice(start,end),this.range=[start,end],this},slice:function(){return this._slice.apply(this,arguments)},_full:function(){return this.file=this.originalFile,this.range=[0,this.size],this},full:function(){return this._full.apply(this,arguments)},_check:function(from,to,options){var lastbits=this.originalFile.slice(from,to),fd=new FormData;fd.append("file",lastbits,this.originalFile.name),fd.append("range",from+"-"+to);var opts=_.extend({},this.ajax,options||{},{data:fd});return $.ajax(this.ajax.url+"/check",opts)},check:function(){return this._check.apply(this,arguments)},_send:function(options){var fd=new FormData;fd.append("file",this.file,this.fileID||this.originalFile.name),fd.append("session",this.sessionID);var opts=_.extend({},this.ajax,options||{},{data:fd}),_self=this;return $.ajax(this.ajax.url+"/upload",opts).done(function(response){!_self.sessionID&&response&&(_self.sessionID=response.session,_self.fileID=response.file)})},send:function(){return this._send.apply(this,arguments)},_upload:function(from,to,options){function loop(){return _super._abort?(_super._abort=!1,void deferred.reject(_super)):max?(deferred.resolve(_super),void _super.done(_super)):(i+parts>to&&(max=!0),void _super.slice(i,max?to:i+parts).load().then(function(){function done(){i=max?to:i+parts,percentage=100*i/to,_super.progress({min:from,max:to,sent:i,percentage:percentage,_super:_super}),rest++,rest>=_super.rest?(setTimeout(loop,_super.restDuration),rest=0):loop()}function err(){deferred.reject.apply(deferred,arguments),_super._abort=!0}_super.send(opts.ajax).then(done,err)},function(err){deferred.reject(err),_super._abort=!0}))}this.beforeStart(this);var deferred=new jQuery.Deferred,opts=_.extend({},this,options);from=_.isNumber(from)?from:0,to=_.isNumber(to)?to:this.size;var parts=1e3*(parts||opts.parts),i=from,max=!1,_super=this,rest=0,percentage=100*i/to;if(i>0){var f=0>from-100?0:from-100;this.check(f,from).then(function(){_super.afterCheck(_super),loop()},deferred.reject)}else loop();return deferred.promise()},upload:function(){return this._upload.apply(this,arguments)},_pause:function(){return this._abort=!0,$.ajax(this.ajax.url+"/killSession",{data:{session:this.sessionID}}),this},pause:function(){return this._pause.apply(this,arguments)},_resume:function(options){var _super=this,opts=_.extend({type:"post",data:{fileID:this.fileID},dataType:"text",mimeType:"text/plain"},options||{});return $.ajax(this.ajax.url+"/resume",opts).then(function(response){_super.upload(+response)})},resume:function(){return this._resume.apply(this,arguments)}},root.FileManager=FileManager}(this);