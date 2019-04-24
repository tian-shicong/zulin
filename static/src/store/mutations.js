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
  }
}

export default mutations
