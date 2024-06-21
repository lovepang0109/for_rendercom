import{y as w,bc as n,o as p,b as f,w as t,p as s,U as a,D as u,P as m,m as P}from"./index.84400ea8.js";import{V as c,a as V,b as h,c as b,d as l,e as g}from"./VRow.5bc57025.js";import{a as I,b as y,V as x}from"./VTextarea.d0bbf08b.js";import{V as C}from"./VDialog.e12e96f6.js";const v={name:"ModifyUserPassword",components:{config:n},props:["isShow"],data(){debugger;return{config:n,dialog:this.isShow,editedItem:{status:"E"},rules:{required:r=>!!r||"This field is required",checkpass:r=>passPattern.test(r)||"Invalid password",confirmPassword:()=>this.editedItem.newPassword===this.editedItem.repassword||"Password does not match"},isPasswordVisible:!1,errors:{},tips:""}},watch:{dialog(r){debugger;r||this.close()},dialogDelete(r){r||this.closeDelete()},isShow(r){console.log(r);debugger;r?this.dialog=!0:(this.dialog=!1,this.close())}},computed:{dialog(r){debugger}},methods:{close(){this.dialog=!1,this.$nextTick(()=>{this.editedItem=Object.assign({},this.defaultItem),this.editedIndex=-1})},async save(){if(!this.editedItem.oldPassword||!this.editedItem.newPassword||!this.editedItem.repassword||this.editedItem.repassword!=this.editedItem.newPassword){passPattern.test(this.editedItem.newPassword)||(this.errors.passErr="Invalid new password."),this.editedItem.newPassword!=this.editedItem.repassword&&(this.errors.rePassErr="Password does not match."),this.editedItem.oldPassword||(this.errors.oldPassErr="Invalid old password"),setTimeout(()=>{this.errors={passErr:"",rePassErr:"",oldPassErr:""}},6e3);return}else{if(!passPattern.test(this.editedItem.newPassword))return;const r=await UpdateUserPassword(this.editedItem.oldPassword,this.editedItem.newPassword,this.userId);r.result.isSuccess?(notify("Login password modified successfully"),this.editedItem.oldPassword="",this.editedItem.newPassword="",this.$emit("close",!0),this.close()):notify(r.result.msg,"error")}}}},k=P("span",{class:"text-h6 mt-4"},"Change Password",-1);function E(r,o,T,U,e,i){return p(),f(C,{modelValue:i.dialog,"onUpdate:modelValue":o[6]||(o[6]=d=>i.dialog=d),"max-width":"500px"},{activator:t(({props:d})=>[]),default:t(()=>[s(c,null,{default:t(()=>[s(V,null,{default:t(()=>[k]),_:1}),s(h,null,{default:t(()=>[s(I,null,{default:t(()=>[s(b,null,{default:t(()=>[s(l,{cols:"12",sm:"12",md:"12"},{default:t(()=>[s(a,{modelValue:e.editedItem.oldPassword,"onUpdate:modelValue":o[0]||(o[0]=d=>e.editedItem.oldPassword=d),label:"Current Password",type:e.isPasswordVisible?"text":"password","append-inner-icon":e.isPasswordVisible?"tabler-eye-off":"tabler-eye","onClick:appendInner":o[1]||(o[1]=d=>e.isPasswordVisible=!e.isPasswordVisible),rules:[e.rules.required],"error-messages":e.errors.oldPassErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),s(l,{cols:"12",sm:"12",md:"12"},{default:t(()=>[s(a,{modelValue:e.editedItem.newPassword,"onUpdate:modelValue":o[2]||(o[2]=d=>e.editedItem.newPassword=d),label:"New Password",type:e.isPasswordVisible?"text":"password","append-inner-icon":e.isPasswordVisible?"tabler-eye-off":"tabler-eye","onClick:appendInner":o[3]||(o[3]=d=>e.isPasswordVisible=!e.isPasswordVisible),rules:[e.rules.required,e.rules.checkpass],"error-messages":e.errors.passErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),s(l,{cols:"12",sm:"12",md:"12"},{default:t(()=>[s(a,{modelValue:e.editedItem.repassword,"onUpdate:modelValue":o[4]||(o[4]=d=>e.editedItem.repassword=d),label:"Re-enter Password",type:e.isPasswordVisible?"text":"password","append-inner-icon":e.isPasswordVisible?"tabler-eye-off":"tabler-eye","onClick:appendInner":o[5]||(o[5]=d=>e.isPasswordVisible=!e.isPasswordVisible),rules:[e.rules.required,e.rules.confirmPassword],"error-messages":e.errors.rePassErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),s(l,{cols:"12",sm:"12",md:"12"},{default:t(()=>[s(y,{label:"Note",color:"warning","no-resize":"",rows:"5",readonly:"",variant:"outlined",focused:!0,"model-value":e.config.passTips},null,8,["model-value"])]),_:1})]),_:1})]),_:1})]),_:1}),s(g,null,{default:t(()=>[s(x),s(u,{color:"blue-darken-1",variant:"text",onClick:i.close},{default:t(()=>[m(" Cancel ")]),_:1},8,["onClick"]),s(u,{color:"blue-darken-1",variant:"text",onClick:i.save},{default:t(()=>[m(" Save ")]),_:1},8,["onClick"])]),_:1})]),_:1})]),_:1},8,["modelValue"])}const B=w(v,[["render",E]]);export{B as default};