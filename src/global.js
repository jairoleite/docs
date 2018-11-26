import Vue from "vue";

//caminho teste
export const baseApiUrl = "../../backend/rest";

export function showError(e) {
  if (e && e.response && e.response.data) {
    Vue.toasted.global.defaultError({ msg: e.response.data });
  } else if (typeof e === "string") {
    Vue.toasted.global.defaultError({ msg: e });
  } else {
    Vue.toasted.global.defaultError();
  }
}

export function toTree(categories, tree) {
  // console.log(categories)
  if (!tree) tree = categories.filter(c => !c.parent);
  tree = tree.map(parentNode => {
    const isChild = node => node.parent == parentNode.id;
    parentNode.children = toTree(categories, categories.filter(isChild));
    return parentNode;
  });
  return tree;
}

export function isEmpty(obj) {
  for (var prop in obj) {
    if (obj.hasOwnProperty(prop)) return false;
  }
  return true;
}

export default { baseApiUrl, showError, toTree, isEmpty };
