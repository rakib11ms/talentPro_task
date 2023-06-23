import MasterDashboardLayout from "../../AdminPanel/MasterDashboardLayout"
import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "axios";
import moment from 'moment';
import Swal from "sweetalert2";
import AddIcon from "@mui/icons-material/Add";
import { DataGrid } from "@mui/x-data-grid";
import CreateOutlinedIcon from "@mui/icons-material/CreateOutlined";
import DeleteOutlineOutlinedIcon from "@mui/icons-material/DeleteOutlineOutlined";
import RemoveRedEyeIcon from "@mui/icons-material/RemoveRedEye";
import SearchIcon from "@mui/icons-material/Search";

function ViewCategory() {
    const [allCategory, setAllCategory] = useState([]);
    const [stateRender, setstateRender] = useState('');



    useEffect(() => {
        axios.get(`/api/category`).then(res => {
            if (res.data.status === 200) {
                setAllCategory(res.data.categories);
            }
        });


    }, [stateRender])


    // const deleteChamber = (e, id) => {
    // }

    const columns = [
        { field: "name", headerName: "Category Name", width: 250 },
        // { field: "chapter_count", headerName: "অধ্যায়ের সংখ্যা", width: 190 },
        // { field: "paragraph_count", headerName: "অনুচ্ছেদের সংখ্যা", width: 190 },
        {
            field: "edit",
            headerName: "সম্পাদনা করুন ",
            width: 190,
            renderCell: (params) => (
                <div className="d-flex justify-content-around align-items-center">
                    <Link to={`/edit-master-book/${params.row.book_id}`}>
                        <CreateOutlinedIcon className="text-warning" />
                    </Link>
                </div>
            ),
        },
        {
            field: "delete",
            headerName: "ডিলিট করুন ",
            width: 190,
            renderCell: (params) => (
                <div className="d-flex justify-content-around align-items-center">
                    {/* sweet alert for confirm delete */}
                    <DeleteOutlineOutlinedIcon
  className="text-danger"
  onClick={() => {
    Swal.fire({
      title: 'Are you sure?',
      text: 'Delete this? ',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        axios
          .delete(`/api/category/${params.row.id}`)
          .then((res) => {
            if (res.data.status === 200) {
                setstateRender(res.data)
              Swal.fire('Your data has been deleted', {
                icon: 'success',
              });
              // Perform any additional actions if needed
            } else {
              Swal.fire('Oops! Something went wrong. Please try again.');
            }
          });
      }
    });
  }}
/>

                </div>
            ),
        },


    ];

    const rows = [
        ...allCategory.map((item) => ({
            id: item.id,
            name: item.name,

        })),
    ];
    return (
        <>
            <MasterDashboardLayout>
                <h3> View Category</h3>

                <div className="row">
                    <div className="col-md-12">

                        <DataGrid
                            rows={rows}
                            columns={columns}
                            initialState={{
                                pagination: {
                                    paginationModel: { page: 0, pageSize: 5 },
                                },
                            }}
                            pageSizeOptions={[5, 10]}
                            checkboxSelection={false}
                        />
                    </div>
                </div>

            </MasterDashboardLayout>
        </>
    )
}

export default ViewCategory