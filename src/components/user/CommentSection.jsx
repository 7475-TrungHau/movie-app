import React, { useState } from "react";

// Dữ liệu mẫu
const initialComments = [
    { id: 1, parent_id: null, user_id: "Alice", content: "Bình luận gốc của Alice." },
    { id: 2, parent_id: 1, user_id: "Bob", content: "Trả lời A từ Bob." },
    { id: 3, parent_id: 2, user_id: "Charlie", content: "Trả lời A từ Charlie." },
    { id: 4, parent_id: 2, user_id: "Dave", content: "Trả lời cho Bob từ Dave." },
    { id: 5, parent_id: null, user_id: "Frank", content: "Bình luận gốc của Frank." },
];

// Hàm xác định bình luận này (có thể là cháu, chắt...) rốt cuộc thuộc bình luận gốc nào
function belongsToRoot(comment, rootId, allComments) {
    let current = comment;
    while (current.parent_id !== null) {
        if (current.parent_id === rootId) return true;
        current = allComments.find((c) => c.id === current.parent_id);
        if (!current) break;
    }
    return false;
}

// Tìm tên user của "cha trực tiếp"
function getParentUserName(comment, allComments) {
    if (!comment.parent_id) return null;
    const parentComment = allComments.find((c) => c.id === comment.parent_id);
    return parentComment ? parentComment.user_id : null;
}

// Form thêm/bình luận
function CommentForm({ parent, onSubmit, onCancel }) {
    const [content, setContent] = useState("");
    const handleSubmit = (e) => {
        e.preventDefault();
        if (!content.trim()) return;
        onSubmit(content, parent);
        setContent("");
    };
    return (
        <form onSubmit={handleSubmit} className="mb-4">
            <textarea
                className="w-full p-2 border border-gray-300 rounded"
                placeholder={parent ? `Reply to @${parent.user_id}` : "Add a new comment..."}
                value={content}
                onChange={(e) => setContent(e.target.value)}
            />
            <div className="mt-2 flex space-x-2">
                <button type="submit" className="bg-blue-500 text-white px-3 py-1 rounded">
                    Gửi
                </button>
                {onCancel && (
                    <button
                        type="button"
                        className="bg-gray-300 px-3 py-1 rounded"
                        onClick={onCancel}
                    >
                        Hủy
                    </button>
                )}
            </div>
        </form>
    );
}

// Hiển thị danh sách bình luận
export default function CommentSection() {
    const [comments, setComments] = useState(initialComments);
    const [replyingTo, setReplyingTo] = useState(null);

    // Thêm bình luận (root hoặc reply)
    const addComment = (content, parent) => {
        const newComment = {
            id: comments.length + 1,
            parent_id: parent ? parent.id : null,
            user_id: "CurrentUser",
            content,
        };
        setComments([...comments, newComment]);
        setReplyingTo(null);
    };

    // Bình luận gốc
    const rootComments = comments.filter((c) => c.parent_id === null);

    // Xử lý khi user nhấn “Reply”
    const handleReply = (comment) => {
        setReplyingTo(comment);
    };

    return (
        <div className="max-w-xl mx-auto p-4">
            <h1 className="text-xl font-bold mb-4">Bình luận</h1>
            {/* Form viết bình luận gốc */}
            <CommentForm onSubmit={addComment} parent={null} />
            {/* Vòng lặp hiển thị mỗi “root” */}
            {rootComments.map((root) => {
                // Lấy tất cả “hậu duệ” thuộc root này, nhưng chỉ hiển thị một cấp indent
                const replies = comments.filter(
                    (c) => c.id !== root.id && belongsToRoot(c, root.id, comments)
                );
                return (
                    <div key={root.id} className="bg-white p-3 mb-4 rounded shadow">
                        {/* Bình luận gốc: không thụt lề */}
                        <div className="mb-2">
                            <span className="font-bold">[{root.user_id}]</span> {root.content}
                        </div>
                        <button
                            className="text-sm text-blue-500 hover:underline"
                            onClick={() => handleReply(root)}
                        >
                            Trả lời
                        </button>
                        {/* Mỗi bình luận con: thụt lề 1 cấp (ml-4) và hiện @cha trực tiếp */}
                        {replies.map((child) => {
                            const parentUser = getParentUserName(child, comments);
                            return (
                                <div key={child.id} className="ml-4 mt-3 p-2 border-l-2 border-gray-300">
                                    <p>
                                        <span className="font-bold">[{child.user_id}]</span>{" "}
                                        {parentUser && <span className="text-blue-600">@{parentUser}</span>}{" "}
                                        {child.content}
                                    </p>
                                    <button
                                        className="text-sm text-blue-500 hover:underline"
                                        onClick={() => handleReply(child)}
                                    >
                                        Trả lời
                                    </button>
                                    <button className="bg"></button>
                                </div>
                            );
                        })}
                    </div>
                );
            })}
            {/* Form trả lời 1 bình luận (nếu có) */}
            {replyingTo && (
                <div className="ml-4">
                    <CommentForm
                        parent={replyingTo}
                        onSubmit={addComment}
                        onCancel={() => setReplyingTo(null)}
                    />
                </div>
            )}
        </div>
    );
}

// import React, { useState } from "react";

// // Data test
// const initialComments = [
//   { id: 1, parent_id: null, user_id: "Alice", content: "Bình luận gốc của Alice về thiết kế mới." },
//   { id: 2, parent_id: 1, user_id: "Bob", content: "Tôi đồng ý, giao diện rất trực quan!" },
//   { id: 3, parent_id: 2, user_id: "Charlie", content: "Màu sắc cũng rất hài hòa." },
//   { id: 4, parent_id: 3, user_id: "Dave", content: "Đúng vậy, tôi thích cách phối màu này." },
//   { id: 5, parent_id: 1, user_id: "Eve", content: "Tôi thấy font chữ hơi khó đọc một chút." },
//   { id: 6, parent_id: null, user_id: "Alice123", content: "Một bình luận khác về tính năng mới." },
//   { id: 7, parent_id: 6, user_id: "Dave", content: "Tôi thấy tính năng này rất hữu ích." },
//   { id: 8, parent_id: 7, user_id: "Eve", content: "Cần cải thiện thêm về hiệu năng." },
// ];

// // Helper functions (unchanged, but kept for completeness)
// function isDescendantOfRoot(comment, rootId, comments) {
//   let current = comment;
//   while (current.parent_id !== null) {
//     if (current.parent_id === rootId) return true;
//     current = comments.find((c) => c.id === current.parent_id);
//     if (!current) break;
//   }
//   return false;
// }

// function getFlattenedChildren(rootId, comments) {
//   return comments.filter((c) => isDescendantOfRoot(c, rootId, comments));
// }

// // --- Components ---

// const Comment = ({ comment, parentUser, onReply }) => {
//   return (
//     <div className="mb-4 p-4 bg-white rounded-lg shadow-md transition-shadow duration-200 hover:shadow-lg">
//       <div className="flex items-start mb-2">
//         <div className="w-10 h-10 rounded-full bg-gray-300 mr-3 flex-shrink-0"></div> {/* Avatar placeholder */}
//         <div>
//           <div className="flex items-center">
//             <strong className="text-gray-800">{comment.user_id}</strong>
//             {parentUser && (
//               <span className="text-blue-500 font-semibold ml-2">@{parentUser}</span>
//             )}
//           </div>
//           <p className="text-gray-600 text-sm">
//             {/*  Placeholder for time/date - you'd use a date library in a real app */}
//             {/* {formatDistanceToNow(comment.createdAt, { addSuffix: true })}  */}
//              1 hour ago
//           </p>
//         </div>
//       </div>
//       <p className="text-gray-700 mb-3">{comment.content}</p>
//       <button
//         onClick={() => onReply(comment)}
//         className="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition-colors duration-150"
//       >
//         Reply
//       </button>
//     </div>
//   );
// };

// const CommentForm = ({ parent, onSubmit }) => {
//   const [content, setContent] = useState("");

//   const handleSubmit = (e) => {
//     e.preventDefault();
//     if (!content.trim()) return;
//     onSubmit(content, parent);
//     setContent("");
//   };

//   return (
//     <form onSubmit={handleSubmit} className="mt-4">
//       <div className="flex items-start">
//         <div className="w-10 h-10 rounded-full bg-gray-300 mr-3 flex-shrink-0"></div> {/* Avatar placeholder */}
//         <div className="flex-1">
//           <textarea
//             placeholder={parent ? `Replying to @${parent.user_id}` : "Add a public comment..."}
//             value={content}
//             onChange={(e) => setContent(e.target.value)}
//             className="w-full px-4 py-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" // resize-none prevents textarea resizing
//             rows="2"
//           />
//            <div className="mt-2 flex justify-end">
//              <button
//                 type="submit"
//                 className="px-6 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 transition-colors duration-150"
//               >
//                 Comment
//               </button>
//             </div>
//         </div>
//       </div>


//     </form>
//   );
// };


// // const CommentSection = () => {
// //   const [comments, setComments] = useState(initialComments);
// //   const [replyingTo, setReplyingTo] = useState(null);

// //   const rootComments = comments.filter((c) => c.parent_id === null);

// //   const addComment = (content, parent) => {
// //     const newId = comments.length > 0 ? Math.max(...comments.map((c) => c.id)) + 1 : 1;
// //     const newComment = {
// //       id: newId,
// //       parent_id: parent ? parent.id : null,
// //       user_id: "You", // Or get the current user's ID
// //       content,
// //       // createdAt: new Date(),  // Add a createdAt timestamp
// //     };
// //     setComments([...comments, newComment]);
// //     setReplyingTo(null);
// //   };

// //   return (
// //     <div className="max-w-3xl mx-auto p-4 bg-gray-100 rounded-lg">  {/* Container with padding */}
// //       <h2 className="text-2xl font-semibold mb-6 text-gray-800">Comments</h2>

// //       <CommentForm onSubmit={addComment} />

// //       {rootComments.map((root) => (
// //         <div key={root.id} className="mb-6">
// //           <Comment comment={root} onReply={setReplyingTo} />
// //           <div className="ml-12">  {/* Indentation for replies */}
// //             {getFlattenedChildren(root.id, comments).map((child) => (
// //               <Comment
// //                 key={child.id}
// //                 comment={child}
// //                 parentUser={comments.find((c) => c.id === child.parent_id)?.user_id}
// //                 onReply={setReplyingTo}
// //               />
// //             ))}
// //           </div>
// //         </div>
// //       ))}

// //       {replyingTo && (
// //         <div className="ml-12">
// //           <CommentForm parent={replyingTo} onSubmit={addComment} />
// //         </div>
// //       )}
// //     </div>
// //   );
// // };

// // export default CommentSection;