import{D as P,_ as b}from"./DialogBox.c4d55d6b.js";import{y as w,E as v,r as p,o as f,c as u,p as a,w as r,R as _,b as H,bl as T,bR as R,D as m,P as D,bM as C,m as s,F as S,a as B,x as c}from"./index.84400ea8.js";import{o as L}from"./main.9984b416.js";import{n as M}from"./index.c0fab4f2.js";import{d as h,V as N,c as g}from"./VRow.5bc57025.js";import{V as A}from"./VAutocomplete.f4bce551.js";import"./VDialog.e12e96f6.js";const{mapGetters:G,mapActions:j,mapState:X}=v("dashboard"),z={components:{DialogBox:P,DashboardNotify:b,VueDatePicker:L},data(){return{isBox:!1,filterPeriod:"Day",picker:"",headers:[{title:"Period",align:"start",key:"Period"},{title:"Action",align:"start",key:"Action"},{title:"Host Name",align:"start",key:"Host"},{title:"Price",align:"start",key:"Price"},{title:"Total Cosmote",align:"start",key:"TotalCosmote"},{title:"Total Vodafone",align:"start",key:"TotalVodafone"},{title:"Total Wind",align:"start",key:"TotalWind"}],filterHost:null}},computed:{...G(["GetDLR","GetServiceData","IsLoading"]),getDlrData:function(){var t,e,o,n;return(e=(t=this.GetDLR)==null?void 0:t.tDlr)!=null&&e.tDlrRow?(n=(o=this.GetDLR)==null?void 0:o.tDlr)==null?void 0:n.tDlrRow:[]},hsLists:function(){var t,e;return(e=(t=this.GetServiceData)==null?void 0:t.tService)==null?void 0:e.tServiceRow.map(o=>o.Host)},periodPicker:function(){return this.filterPeriod=="Month"}},mounted(){this.fetchServiceData();const t=new Date,e=new Date(new Date().setDate(t.getDate()-7));this.picker=[e,t],this.fetchDlrData({period:this.filterPeriod,range:`${this.formatDate(e)}#${this.formatDate(t)}`})},methods:{...j(["fetchDlrData","fetchServiceData"]),formatDate(t){var e=new Date(t),o=""+(e.getMonth()+1),n=""+e.getDate(),i=e.getFullYear();return o.length<2&&(o="0"+o),n.length<2&&(n="0"+n),[n,o,i].join("-")},formatMonth(t){var e=new Date(t);if(e!="Invalid Date"){let o=""+(e.getMonth()+1),n=e.getFullYear();return o.length<2&&(o="0"+o),[o,n].join("-")}else{let o=""+((t==null?void 0:t.month)+1),n=t==null?void 0:t.year;return o.length<2&&(o="0"+o),[o,n].join("-")}},ackSearch(){if(!this.picker){M("Please select the dater range","error");return}let t=this.picker[0],e=this.picker[1];this.filterPeriod=="Day"?this.fetchDlrData({period:this.filterPeriod,range:`${this.formatDate(t)}#${this.formatDate(e)}`,host:this.filterHost?this.filterHost.join(","):""}):this.fetchDlrData({period:this.filterPeriod,range:`${this.formatMonth(t)}#${this.formatMonth(e)}`,host:this.filterHost?this.filterHost.join(","):""})},Reset(){this.filterHost="",this.filterPeriod="Day";const t=new Date,e=new Date(new Date().setDate(t.getDate()-7));this.picker=[e,t]}},watch:{}},F={key:1,class:"px-4 py-4",style:{"min-height":"400px"}},W=s("thead",{class:"text-center"},[s("tr",null,[s("th",{scope:"col",class:"text-center"},"Period"),s("th",{scope:"col",class:"text-center"},"Action"),s("th",{scope:"col",class:"text-center"},"HostName"),s("th",{scope:"col",class:"text-center"},"Price"),s("th",{scope:"col",class:"text-center"},"TotalCosmote"),s("th",{scope:"col",class:"text-center"},"TotalVodafone"),s("th",{scope:"col",class:"text-center"},"TotalWind")])],-1),E={class:"text-center"};function I(t,e,o,n,i,d){const k=p("VueDatePicker"),y=p("download-excel"),V=p("DialogBox");return f(),u("div",null,[a(g,{class:"match-height"},{default:r(()=>[a(h,{cols:"12",md:"12",lg:"12"},{default:r(()=>[a(N,{class:"text-center py-4",title:"DLR Data"},{default:r(()=>[a(_),t.IsLoading?(f(),H(T,{key:0,size:50,color:"primary",indeterminate:""})):(f(),u("div",F,[a(g,{class:"align-center"},{default:r(()=>[a(h,{cols:"12",md:"3",sm:"6"},{default:r(()=>[a(A,{clearable:"",multiple:"",label:"Host",items:d.hsLists,modelValue:i.filterHost,"onUpdate:modelValue":e[0]||(e[0]=l=>i.filterHost=l)},null,8,["items","modelValue"])]),_:1}),a(h,{cols:"12",md:"2",sm:"6"},{default:r(()=>[a(R,{label:"Period",items:["Day","Month"],modelValue:i.filterPeriod,"onUpdate:modelValue":e[1]||(e[1]=l=>i.filterPeriod=l)},null,8,["modelValue"])]),_:1}),a(h,{cols:"12",md:"3",sm:"6"},{default:r(()=>[a(k,{modelValue:i.picker,"onUpdate:modelValue":e[2]||(e[2]=l=>i.picker=l),range:"","enable-time-picker":!1,"month-picker":d.periodPicker},null,8,["modelValue","month-picker"])]),_:1}),a(h,{cols:"12",md:"4",sm:"6",class:"text-center"},{default:r(()=>[a(m,{onClick:d.ackSearch,color:"info",dark:"",class:"btn border",size:"small","prepend-icon":"tabler-search"},{default:r(()=>[D(" Search ")]),_:1},8,["onClick"]),a(m,{color:"secondary",dark:"",class:"btn border ml-4",size:"small","prepend-icon":"tabler-refresh",onClick:d.Reset},{default:r(()=>[D(" Reset ")]),_:1},8,["onClick"]),a(y,{data:d.getDlrData,class:"d-inline"},{default:r(()=>[a(m,{color:"success",dark:"",class:"btn border ml-4",size:"small","prepend-icon":"mdi-file-excel"},{default:r(()=>[D(" Excel ")]),_:1})]),_:1},8,["data"])]),_:1})]),_:1}),a(C,{class:"text-no-wrap text-center py-4"},{default:r(()=>[W,s("tbody",E,[(f(!0),u(S,null,B(d.getDlrData,(l,x)=>(f(),u("tr",{style:{height:"3.75rem"},key:x},[s("td",null,c(l==null?void 0:l.Period),1),s("td",null,c(l==null?void 0:l.Action),1),s("td",null,c(l==null?void 0:l.Host),1),s("td",null,c(l==null?void 0:l.Price),1),s("td",null,c(l==null?void 0:l.TotalCosmote),1),s("td",null,c(l==null?void 0:l.TotalVodafone),1),s("td",null,c(l==null?void 0:l.TotalWind),1)]))),128))])]),_:1}),a(_),a(g,{class:"align-left pt-4"},{default:r(()=>[a(h,{cols:"12",md:"6",sm:"6"}),a(h,{cols:"12",md:"6",sm:"6",class:"text-right pr-4"},{default:r(()=>[D(" Total Rows: "+c(d.getDlrData.length),1)]),_:1})]),_:1})]))]),_:1})]),_:1})]),_:1}),a(V,{isVisable:i.isBox},null,8,["isVisable"])])}const Z=w(z,[["render",I]]);export{Z as default};