import{D as _,_ as k}from"./DialogBox.c4d55d6b.js";import{y as v,E as C,r as f,o as h,c as P,p as s,w as n,R as S,b as g,bl as N,ba as M,D,P as R,m as c,b5 as I,x as u}from"./index.84400ea8.js";import{o as E}from"./main.9984b416.js";import{d,V as L,c as p}from"./VRow.5bc57025.js";import{V as m}from"./VAutocomplete.f4bce551.js";import"./VDialog.e12e96f6.js";const{mapGetters:w,mapActions:B,mapState:H}=C("dashboard"),G={components:{DialogBox:_,DashboardNotify:k,VueDatePicker:E},data(){return{picker:"",isBox:!1,headers:[{title:"Destination Country",align:"start",key:"Pais_Destino"},{title:"Sender",align:"start",key:"Remitente"},{title:"Submited",align:"start",key:"Enviados"},{title:"Notified",align:"start",key:"Notificados"},{title:"Wrong",align:"start",key:"Errores"},{title:"Forwarded",align:"start",key:"Reenviados"},{title:"Delivered",align:"start",key:"Entregados"}],countryModel:null,providerModel:null,customerModel:null,typeRouteModel:null,mccmncModel:null,serverUrl:window.configData.APP_BASE_URL}},computed:{...w(["GetReportSenders","IsLoading","GetReportMccMnc"]),getReportData:function(){var t,e,l;return(t=this.GetReportSenders)!=null&&t.data?(l=(e=this.GetReportSenders)==null?void 0:e.data)==null?void 0:l.filter(a=>a.Pais_Destino).map(a=>{let r=a;return r.OutText=decodeURIComponent(a==null?void 0:a.OutText),r}):[]},getCountries:function(){var t,e;return(e=(t=this.GetReportSenders)==null?void 0:t.countryList)==null?void 0:e.map(l=>`${l.state}[${l.code}]`).splice(0,50)},getProviders:function(){var t,e;return(e=(t=this.GetReportSenders)==null?void 0:t.providerList)==null?void 0:e.map(l=>`${l.NOMBRE}[${l.ID_PROVEEDOR}]`).splice(0,50)},getCustomers:function(){var t,e;return(e=(t=this.GetReportSenders)==null?void 0:t.customerList)==null?void 0:e.map(l=>`${l.EMPRESA}[${l.ID_CLIENTE}]`).splice(0,50)},getTypeRoute:function(){var e,l;const t=((e=this.GetReportSenders)==null?void 0:e.typeRoute)||{};return(l=Object.keys(t))==null?void 0:l.map(a=>`${t[a]} [${a.substr(1,1)}]`)},getMccMnc:function(){var t,e;return(e=(t=this.GetReportMccMnc)==null?void 0:t.map(l=>`${l.OPERATOR} [${l.MCC}${l.MNC}]`).sort())!=null?e:[]},periodPicker:function(){return this.filterPeriod=="Month"}},mounted(){const t=new Date,e=new Date(new Date().setDate(t.getDate()-1));this.picker=[e,t],this.fetchReportSenderData({cFechaInicio:e,cFechaFin:t,cPaises:"",iIDCliente:"",iIDProveedor:"",iIDTipoRuta:-1,cMccMnc:""})},methods:{...B(["fetchReportSenderData","fetchMccMncData"]),searchResult(){this.fetchReportSenderData({cFechaInicio:this.picker[0],cFechaFin:this.picker[1],cPaises:this.getNumList(this.countryModel),iIDCliente:this.getNumList(this.customerModel),iIDProveedor:this.getNumList(this.providerModel),iIDTipoRuta:this.getNumList(this.typeRouteModel),cMccMnc:this.getMccMncLists(this.mccmncModel)})},getMccMncLists(t){const e=JSON.parse(JSON.stringify(t));return e?e.map(l=>parseInt(l.match(/\d+/)[0])).join(","):""},Reset(){this.countryModel=null,this.providerModel=null,this.customerModel=null,this.typeRouteModel=null,this.mccmncModel=null;const t=new Date,e=new Date(new Date().setDate(t.getDate()-1));this.picker=[e,t]},getNumList(t){return Array.isArray(t)?t.map(e=>parseInt(e.match(/\d+/)[0])).join(","):t}},watch:{countryModel:function(t){const e=this.getNumList(t);!e||this.fetchMccMncData({codes:e})}}},O={class:"text-left"};function T(t,e,l,a,r,i){const y=f("VueDatePicker"),b=f("v-data-table"),V=f("DialogBox");return h(),P("div",null,[s(p,{class:"match-height"},{default:n(()=>[s(d,{cols:"12",md:"12",lg:"12"},{default:n(()=>[s(L,{class:"text-center pb-4",title:"Search Reports for Senders"},{default:n(()=>[s(S,{class:"pb-1"}),t.IsLoading?(h(),g(N,{key:0,size:50,color:"primary",indeterminate:""})):M("",!0),t.IsLoading?M("",!0):(h(),g(b,{key:1,headers:r.headers,items:i.getReportData,"sort-by":[{key:"calories",order:"asc"}],class:"elevation-1 px-4 py-2",style:{"min-height":"450px"}},{top:n(()=>[s(p,{class:"align-center"},{default:n(()=>[s(d,{cols:"12",md:"4",sm:"6"},{default:n(()=>[s(y,{modelValue:r.picker,"onUpdate:modelValue":e[0]||(e[0]=o=>r.picker=o),range:"","enable-time-picker":!1,"month-picker":i.periodPicker},null,8,["modelValue","month-picker"])]),_:1}),s(d,{cols:"12",md:"4",sm:"6"},{default:n(()=>[s(m,{modelValue:r.countryModel,"onUpdate:modelValue":e[1]||(e[1]=o=>r.countryModel=o),label:"Countries",items:i.getCountries,multiple:""},null,8,["modelValue","items"])]),_:1}),s(d,{cols:"12",md:"4",sm:"6"},{default:n(()=>[s(m,{label:"Providers",items:i.getProviders,modelValue:r.providerModel,"onUpdate:modelValue":e[2]||(e[2]=o=>r.providerModel=o),multiple:""},null,8,["items","modelValue"])]),_:1})]),_:1}),s(p,{class:"align-center"},{default:n(()=>[s(d,{cols:"12",md:"4",sm:"6"},{default:n(()=>[s(m,{modelValue:r.customerModel,"onUpdate:modelValue":e[3]||(e[3]=o=>r.customerModel=o),label:"Customers",items:i.getCustomers,multiple:""},null,8,["modelValue","items"])]),_:1}),s(d,{cols:"12",md:"4",sm:"6"},{default:n(()=>[s(m,{modelValue:r.mccmncModel,"onUpdate:modelValue":e[4]||(e[4]=o=>r.mccmncModel=o),label:"MccMnc",items:i.getMccMnc,multiple:"",disabled:!i.getMccMnc.length},null,8,["modelValue","items","disabled"])]),_:1}),s(d,{cols:"12",md:"4",sm:"6"},{default:n(()=>[s(m,{label:"Type of route",items:i.getTypeRoute,modelValue:r.typeRouteModel,"onUpdate:modelValue":e[5]||(e[5]=o=>r.typeRouteModel=o),multiple:""},null,8,["items","modelValue"])]),_:1})]),_:1}),s(p,null,{default:n(()=>[s(d,{cols:"12",md:"12",sm:"12",class:"text-right"},{default:n(()=>[s(D,{onClick:i.searchResult,color:"info",dark:"",class:"btn border",size:"small","prepend-icon":"tabler-search"},{default:n(()=>[R(" Search ")]),_:1},8,["onClick"]),s(D,{color:"secondary",dark:"",class:"btn border ml-4",size:"small","prepend-icon":"tabler-refresh",onClick:i.Reset},{default:n(()=>[R(" Reset ")]),_:1},8,["onClick"])]),_:1}),s(d,{cols:"12",md:"3",sm:"12"})]),_:1})]),item:n(({item:o,index:U})=>[c("tr",O,[c("td",null,[s(I,{"max-width":40,src:`${r.serverUrl}/flags/${o.selectable.Pais_Destino}.png`},null,8,["src"]),c("span",null,u(o.selectable.state),1)]),c("td",null,u(o.selectable.Remitente.replaceAll("+"," ")),1),c("td",null,u(o.selectable.Enviados),1),c("td",null,u(o.selectable.Notificados),1),c("td",null,u(o.selectable.Errores),1),c("td",null,u(o.selectable.Reenviados),1),c("td",null,u(o.selectable.Entregados),1)])]),_:1},8,["headers","items"]))]),_:1})]),_:1})]),_:1}),s(V,{isVisable:r.isBox},null,8,["isVisable"])])}const W=v(G,[["render",T]]);export{W as default};