const getters = {
  paginator: state => {
    return state.paginator;
  },
  getCanalById: state => {
    return state.canal;
  },
  getConteudos: state => {
    return state.conteudos;
  },
  getErrors: state => {
    return state.errors;
  },
  getCanal: state => {
    return state.canal;
  },
  getSidebar: state => {
    return state.sidebar;
  },
  getshowConteudo: state => {
    return state.showConteudo;
  },
  getshowAplicativo: state => {
    return state.showAplicativo;
  },
  getTipos: state => {
    return state.tipos;
  },
  getshowLicenses: state => {
    return state.licenses;
  },
  getFormData: state => {
    return state.formData;
  },
  getConteudo: state => {
    return state.conteudo;
  },
  getButtonText: state => {
    return state.buttonText;
  }
};

export default getters;
