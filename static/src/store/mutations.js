/**
 * Created by administrator on 2019/4/23.
 */
const mutations = {
  setUserSession(state, n){
    state.user = n;
    sessionStorage.setItem('user', JSON.stringify(n));
  },
  removeUserSession(state){
    state.user = null;
    sessionStorage.removeItem('user');
  },
  setActiveIndex(state, n){
    state.activeIndex = n;
  },
  setEditUser(state, n){
    state.isEditUser = n;
  },
  setLoading(state, n){
    state.isLoading = n;
  },
  reload(state,n){
    state.viewShow = n;
    if(n){
      state.isLoading = false;
    }else {
      state.isLoading = true;
    }
  }
}

export default mutations
